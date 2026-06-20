<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ItemVariant;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class CheckoutController extends Controller
{
    /**
     * Display checkout page
     */
    public function index()
    {
        // Check if cart is empty
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Get cart items with details
        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $item) {
            $variant = ItemVariant::with(['item.images'])->find($item['variant_id']);
            if ($variant) {
                $itemTotal = $variant->price * $item['quantity'];
                $subtotal += $itemTotal;
                $cartItems[] = [
                    'variant' => $variant,
                    'quantity' => $item['quantity'],
                    'subtotal' => $itemTotal
                ];
            }
        }

        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty or items are no longer available.');
        }

        // Get customer addresses
        $addresses = Address::where('user_id', Auth::id())
                          ->orderBy('is_default', 'desc')
                          ->get();

        // Calculate shipping (basic logic)
        $shipping = 0;
        if ($subtotal < 50000) {
            $shipping = 3000; // Base shipping cost
        }

        $total = $subtotal + $shipping;

        return view('customer.checkout', compact('cartItems', 'subtotal', 'shipping', 'total', 'addresses'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'address_line' => 'required|string',
            'township' => 'required|string|max:100',
            'region' => 'required|string|max:100',
            'payment_method' => 'required|in:cod,kpay,wave',
            'payment_slip' => 'required_if:payment_method,kpay,wave|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Your cart is empty.');
        }

        // Get cart items
        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $item) {
            $variant = ItemVariant::with(['item.images'])->find($item['variant_id']);
            if (!$variant || $variant->stock_quantity < $item['quantity']) {
                return back()->with('error', 'Some items are no longer available or have insufficient stock.');
            }
            
            $itemTotal = $variant->price * $item['quantity'];
            $subtotal += $itemTotal;
            $cartItems[] = [
                'variant' => $variant,
                'quantity' => $item['quantity'],
                'subtotal' => $itemTotal
            ];
        }

        if (empty($cartItems)) {
            return back()->with('error', 'Your cart is empty.');
        }

        // Calculate shipping
        $shipping = 0;
        if ($subtotal < 50000) {
            $shipping = 3000;
        }

        $total = $subtotal + $shipping;

        // Combine full shipping address
        $fullShippingAddress = "Name: {$request->full_name}\n"
                             . "Phone: {$request->phone}\n"
                             . "Email: {$request->email}\n"
                             . "Address: {$request->address_line}\n"
                             . "Township: {$request->township}\n"
                             . "Region: {$request->region}";

        DB::beginTransaction();
        $paymentSlipPath = null;

        try {
            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $total,
                'payment_status' => 'pending',
                'status' => 'pending',
            ]);

            // Create order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $cartItem['variant']->item_id,
                    'quantity' => $cartItem['quantity'],
                    'price' => $cartItem['variant']->price,
                ]);

                // Update stock
                $cartItem['variant']->decrement('stock_quantity', $cartItem['quantity']);
            }

            // Create payment record
            if ($request->hasFile('payment_slip')) {
                $paymentSlipPath = $request->file('payment_slip')->store('payments', 'public');
            }

            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $request->payment_method,
                'transaction_id' => $request->phone, // Using phone as reference
                'screenshot_image' => $paymentSlipPath,
                'status' => $request->payment_method === 'cod' ? 'pending' : 'pending_verification',
            ]);

            // Update order with shipping info
            $order->update([
                'shipping_address' => $fullShippingAddress,
                'payment_method' => $request->payment_method,
            ]);

            // Clear cart
            Session::forget('cart');

            DB::commit();

            return redirect()->route('checkout.success', $order->id)
                           ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded file if exists
            if ($paymentSlipPath && Storage::disk('public')->exists($paymentSlipPath)) {
                Storage::disk('public')->delete($paymentSlipPath);
            }

            \Illuminate\Support\Facades\Log::error('Checkout failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return back()->with('error', 'An error occurred while processing your order. Please try again.');
        }
    }

    /**
     * Display order success page
     */
    public function success($orderId)
    {
        $order = Order::with('items', 'payment')->findOrFail($orderId);
        
        // Verify this order belongs to the current user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('customer.order_success', compact('order'));
    }

    /**
     * Add new address during checkout
     */
    public function addAddress(Request $request)
    {
        $request->validate([
            'label' => 'nullable|string|max:100',
            'address_line' => 'required|string',
            'city' => 'required|string|max:100',
            'phone' => 'required|string|max:50',
            'is_default' => 'nullable|boolean',
        ]);

        // If setting as default, remove default from other addresses
        if ($request->has('is_default')) {
            Address::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        $address = Address::create([
            'user_id' => Auth::id(),
            'label' => $request->label,
            'address_line' => $request->address_line,
            'city' => $request->city,
            'phone' => $request->phone,
            'is_default' => $request->has('is_default'),
        ]);

        return back()->with('success', 'Address added successfully!');
    }

    /**
     * Validate coupon code
     */
    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        // This would check against coupons table
        // For now, just return a placeholder response
        return response()->json([
            'valid' => false,
            'message' => 'Coupon code is not valid'
        ]);
    }
}