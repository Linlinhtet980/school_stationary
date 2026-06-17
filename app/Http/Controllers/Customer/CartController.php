<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ItemVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Get cart items from session
     */
    private function getCart()
    {
        return Session::get('cart', []);
    }

    /**
     * Save cart items to session
     */
    private function saveCart($cart)
    {
        Session::put('cart', $cart);
    }

    /**
     * Display cart page
     */
    public function index()
    {
        $cart = $this->getCart();
        $cartItems = collect();
        $subtotal = 0;
        $shipping = 0;
        $total = 0;

        foreach ($cart as $key => $item) {
            $variant = ItemVariant::with('item')->find($item['variant_id']);
            if ($variant && $variant->item) {
                $itemTotal = $variant->price * $item['quantity'];
                $subtotal += $itemTotal;
                
                $cartItems->push([
                    'key' => $key,
                    'item' => $variant->item->toArray(),
                    'variant' => $variant->toArray(),
                    'quantity' => $item['quantity'],
                    'total' => $itemTotal
                ]);
            }
        }

        // Free shipping for orders over 50,000 Ks
        $shipping = $subtotal >= 50000 ? 0 : 3000;
        $total = $subtotal + $shipping;

        return view('customer.cart', compact('cartItems', 'subtotal', 'shipping', 'total'));
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:item_variants,id',
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        $variantId = $request->variant_id;
        $quantity = $request->quantity;
        $cart = $this->getCart();

        // Check if variant exists and has enough stock
        $variant = ItemVariant::find($variantId);
        if (!$variant || $variant->stock_quantity < $quantity) {
            return back()->with('error', 'Not enough stock available.');
        }

        // Check if item already exists in cart
        $found = false;
        foreach ($cart as &$item) {
            if ($item['variant_id'] == $variantId) {
                $newQuantity = $item['quantity'] + $quantity;
                if ($newQuantity > $variant->stock_quantity) {
                    return back()->with('error', 'Cannot add more than available stock.');
                }
                $item['quantity'] = $newQuantity;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $cart[] = [
                'variant_id' => $variantId,
                'quantity' => $quantity
            ];
        }

        $this->saveCart($cart);
        return redirect()->route('cart.index')->with('success', 'Item added to cart!');
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request)
    {
        $request->validate([
            'key' => 'required|integer',
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        $key = $request->key;
        $quantity = $request->quantity;
        $cart = $this->getCart();

        if (!isset($cart[$key])) {
            return back()->with('error', 'Item not found in cart.');
        }

        // Check stock availability
        $variant = ItemVariant::find($cart[$key]['variant_id']);
        if (!$variant || $variant->stock_quantity < $quantity) {
            return back()->with('error', 'Not enough stock available.');
        }

        // Update quantity
        $cart[$key]['quantity'] = $quantity;
        $this->saveCart($cart);
        
        return back()->with('success', 'Cart updated!');
    }

    /**
     * Remove item from cart
     */
    public function remove(Request $request)
    {
        $request->validate([
            'key' => 'required|integer'
        ]);

        $key = $request->key;
        $cart = $this->getCart();

        if (!isset($cart[$key])) {
            return back()->with('error', 'Item not found in cart.');
        }

        // Remove item from cart
        unset($cart[$key]);
        $this->saveCart($cart);
        
        return back()->with('success', 'Item removed from cart!');
    }

    /**
     * Clear all items from cart
     */
    public function clear()
    {
        Session::forget('cart');
        return back()->with('success', 'Cart cleared!');
    }

    /**
     * Get cart count (for header display)
     */
    public function getCount()
    {
        $cart = $this->getCart();
        $count = 0;
        foreach ($cart as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }

    /**
     * Get cart total (for header display)
     */
    public function getTotal()
    {
        $cart = $this->getCart();
        $total = 0;
        foreach ($cart as $item) {
            $variant = ItemVariant::find($item['variant_id']);
            if ($variant) {
                $total += $variant->price * $item['quantity'];
            }
        }
        return $total;
    }
}