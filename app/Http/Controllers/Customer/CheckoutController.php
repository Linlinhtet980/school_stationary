<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ItemVariant;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $key => $item) {
            $variant = ItemVariant::with(['item.images'])->find($item['variant_id']);
            if ($variant) {
                $price = $item['custom_price'] ?? $variant->price;
                $itemTotal = $price * $item['quantity'];
                $subtotal += $itemTotal;
                $cartItems[] = [
                    'key'      => $key,
                    'variant'  => $variant,
                    'quantity' => $item['quantity'],
                    'price'    => $price,
                    'subtotal' => $itemTotal
                ];
            }
        }

        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty or items are no longer available.');
        }

        $addresses = Address::where('user_id', Auth::id())
            ->orderBy('is_default', 'desc')
            ->get();

        // 1500 Ks shipping for bundle items, regular 3000 Ks otherwise (free over 50,000 Ks)
        $hasBundleItems = collect($cart)->contains(fn($item) => !empty($item['bundle_id']));
        $shipping = $hasBundleItems ? 1500 : ($subtotal < 50000 ? 3000 : 0);
        $total    = $subtotal + $shipping;

        return view('customer.checkout', compact('cartItems', 'subtotal', 'shipping', 'total', 'addresses'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'full_name'      => 'required|string|max:255',
            'phone'          => 'required|string|max:50',
            'email'          => 'required|email|max:255',
            'address_line'   => 'required|string',
            'township'       => 'required|string|max:100',
            'region'         => 'required|string|max:100',
            'payment_method' => 'required|in:cod,kpay,wave',
        ]);

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Your cart is empty.');
        }

        $cartItems = [];
        $subtotal  = 0;

        foreach ($cart as $item) {
            $variant = ItemVariant::with(['item'])->find($item['variant_id']);
            if (!$variant || $variant->stock_quantity < $item['quantity']) {
                return back()->with('error', 'Some items are unavailable.');
            }
            $price = $item['custom_price'] ?? $variant->price;
            $itemTotal = $price * $item['quantity'];
            $subtotal += $itemTotal;
            $cartItems[] = [
                'variant'  => $variant,
                'quantity' => $item['quantity'],
                'price'    => $price,
                'subtotal' => $itemTotal,
            ];
        }

        // 1500 Ks shipping for bundle items, regular 3000 Ks otherwise (free over 50,000 Ks)
        $hasBundleItems = collect($cart)->contains(fn($item) => !empty($item['bundle_id']));
        $shipping = $hasBundleItems ? 1500 : ($subtotal < 50000 ? 3000 : 0);
        $total    = $subtotal + $shipping;

        Session::put('checkout_data', [
            'full_name'      => $request->full_name,
            'phone'          => $request->phone,
            'email'          => $request->email,
            'address_line'   => $request->address_line,
            'township'       => $request->township,
            'region'         => $request->region,
            'payment_method' => $request->payment_method,
            'total'          => $total,
            'shipping'       => $shipping,
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        $lineItems = [];
        foreach ($cartItems as $ci) {
            // Using actual item name and variant details
            $itemName = $ci['variant']->item->name;
            if ($ci['variant']->unit_label) {
                $itemName .= ' (' . $ci['variant']->unit_label . ')';
            }

            $lineItems[] = [
                'price_data' => [
                    'currency'     => 'usd', // Stripe requires a supported currency
                    // Multiply by 100 because Stripe expects the amount in the smallest unit (e.g., cents for USD)
                    // If price is 1500, passing 150000 will display as $1500.00
                    'unit_amount'  => (int) ($ci['variant']->price * 100),
                    'product_data' => [
                        'name' => $itemName,
                    ],
                ],
                'quantity' => $ci['quantity'],
            ];
        }

        // Add Shipping Fee as a separate line item if applicable
        if ($shipping > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency'     => 'usd',
                    'unit_amount'  => (int) ($shipping * 100),
                    'product_data' => [
                        'name' => 'Shipping Fee',
                    ],
                ],
                'quantity' => 1,
            ];
        }

        $stripeSession = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items'           => $lineItems,
            'mode'                 => 'payment',
            'success_url'          => route('checkout.stripe-success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'           => route('checkout.index'),
            'customer_email'       => $request->email,
        ]);

        return redirect($stripeSession->url);
    }

    public function stripeSuccess(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $stripeSession = StripeSession::retrieve($request->session_id);

        if ($stripeSession->payment_status !== 'paid') {
            return redirect()->route('checkout.index')->with('error', 'Payment was not completed.');
        }

        $checkoutData = Session::get('checkout_data');
        if (!$checkoutData) {
            return redirect()->route('checkout.index')->with('error', 'Session expired. Please try again.');
        }

        $cart = Session::get('cart', []);

        $cartItems = [];
        foreach ($cart as $item) {
            $variant     = ItemVariant::find($item['variant_id']);
            $itemTotal   = $variant->price * $item['quantity'];
            $cartItems[] = [
                'variant'  => $variant,
                'quantity' => $item['quantity'],
                'subtotal' => $itemTotal,
            ];
        }

        $fullShippingAddress = "Name: {$checkoutData['full_name']}\n"
            . "Phone: {$checkoutData['phone']}\n"
            . "Email: {$checkoutData['email']}\n"
            . "Address: {$checkoutData['address_line']}\n"
            . "Township: {$checkoutData['township']}\n"
            . "Region: {$checkoutData['region']}";

        DB::beginTransaction();
        try {
            // Auto-save address if it doesn't exist
            $existingAddress = Address::where('user_id', Auth::id())
                ->where('address_line', $checkoutData['address_line'])
                ->where('phone', $checkoutData['phone'])
                ->first();

            if (!$existingAddress) {
                $hasAddresses = Address::where('user_id', Auth::id())->exists();
                Address::create([
                    'user_id'      => Auth::id(),
                    'label'        => 'Checkout Address',
                    'address_line' => $checkoutData['address_line'],
                    'city'         => $checkoutData['township'] . ', ' . $checkoutData['region'],
                    'phone'        => $checkoutData['phone'],
                    'is_default'   => !$hasAddresses,
                ]);
            }

            $order = Order::create([
                'user_id'         => Auth::id(),
                'order_number'     => 'ORD-' . strtoupper(uniqid()),
                'total_amount'     => $checkoutData['total'],
                'shipping_address' => $fullShippingAddress,
                'shipping_city'    => $checkoutData['region'],
                'shipping_phone'   => $checkoutData['phone'],
                'bus_gate'         => $checkoutData['township'],
                'payment_method'   => $checkoutData['payment_method'],
                'payment_status'   => 'paid',
                'status'           => 'pending',
            ]);

            foreach ($cartItems as $ci) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_variant_id' => $ci['variant']->id,
                    'quantity' => $ci['quantity'],
                    'unit_price' => $ci['variant']->price,
                    'total_price' => $ci['subtotal'],
                ]);
                $ci['variant']->decrement('stock_quantity', $ci['quantity']);
            }

            Payment::create([
                'order_id'       => $order->id,
                'payment_method' => 'stripe',
                'transaction_id' => $stripeSession->payment_intent,
                'status'         => 'verified',
            ]);

            Session::forget('cart');
            Session::forget('checkout_data');

            DB::commit();

            // Notify Staff Members
            $admins = User::whereHas('role', function($q) {
                $q->where('name', '!=', 'Customer');
            })->get();
            Notification::send($admins, new NewOrderNotification($order));

            return redirect()->route('checkout.success', $order->id)
                ->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Stripe success failed: ' . $e->getMessage());
            return redirect()->route('checkout.index')->with('error', 'Something went wrong.');
        }
    }

    public function success($orderId)
    {
        $order = Order::with('items', 'payment')->findOrFail($orderId);

        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('customer.order_success', compact('order'));
    }

    public function addAddress(Request $request)
    {
        $request->validate([
            'label'        => 'nullable|string|max:100',
            'address_line' => 'required|string',
            'city'         => 'required|string|max:100',
            'phone'        => 'required|string|max:50',
            'is_default'   => 'nullable|boolean',
        ]);

        if ($request->has('is_default')) {
            Address::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        Address::create([
            'user_id'      => Auth::id(),
            'label'        => $request->label,
            'address_line' => $request->address_line,
            'city'         => $request->city,
            'phone'        => $request->phone,
            'is_default'   => $request->has('is_default'),
        ]);

        return back()->with('success', 'Address added successfully!');
    }

    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        return response()->json([
            'valid'   => false,
            'message' => 'Coupon code is not valid'
        ]);
    }
}
