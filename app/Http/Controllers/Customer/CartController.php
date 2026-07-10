<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Item;
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
                $price = $item['custom_price'] ?? $variant->price;
                $itemTotal = $price * $item['quantity'];
                $subtotal += $itemTotal;
                
                $cartItems->push([
                    'key' => $key,
                    'item' => $variant->item->toArray(),
                    'variant' => $variant->toArray(),
                    'quantity' => $item['quantity'],
                    'price' => $price,
                    'total' => $itemTotal
                ]);
            }
        }

        // Check if cart has any bundle items
        $hasBundleItems = collect($cart)->contains(fn($item) => !empty($item['bundle_id']));

        // We now calculate shipping dynamically at checkout based on region.
        // For the cart view, we just show 0 or an estimate.
        $shipping = 0;
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
            'quantity' => 'required|integer|min:1'
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
        foreach ($cart as $key => $item) {
            if ($item['variant_id'] == $variantId) {
                $newQuantity = $item['quantity'] + $quantity;
                if ($newQuantity > $variant->stock_quantity) {
                    return back()->with('error', 'Cannot add more than available stock.');
                }
                $cart[$key]['quantity'] = $newQuantity;
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

        if ($request->ajax() || $request->wantsJson()) {
            $cartCount = array_sum(array_column($cart, 'quantity'));
            return response()->json(['success' => true, 'cart_count' => $cartCount]);
        }

        return back()->with('cart_open', true)->with('success', 'Item added to cart!');
    }

    /**
     * Add item to cart by Item ID (auto-selects first available variant)
     */
    public function addByItem(Request $request, $itemId)
    {
        $item = Item::with('variants')->findOrFail($itemId);

        // Auto-select first in-stock variant
        $variant = $item->variants->firstWhere('stock_quantity', '>', 0)
                   ?? $item->variants->first();

        if (!$variant) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'This item has no available variants.'], 400);
            }
            return back()->with('error', 'This item has no available variants.');
        }

        $quantity = max(1, (int) $request->input('quantity', 1));
        $cart = $this->getCart();

        // Check stock
        if ($variant->stock_quantity < $quantity) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Not enough stock available.'], 400);
            }
            return back()->with('error', 'Not enough stock available.');
        }

        $found = false;
        foreach ($cart as $key => $cartItem) {
            if ($cartItem['variant_id'] == $variant->id) {
                $newQty = $cartItem['quantity'] + $quantity;
                if ($newQty > $variant->stock_quantity) {
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json(['success' => false, 'message' => 'Cannot add more than available stock.'], 400);
                    }
                    return back()->with('error', 'Cannot add more than available stock.');
                }
                $cart[$key]['quantity'] = $newQty;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $cart[] = [
                'variant_id' => $variant->id,
                'quantity'   => $quantity,
            ];
        }

        $this->saveCart($cart);

        if ($request->ajax() || $request->wantsJson()) {
            $cartCount = array_sum(array_column($cart, 'quantity'));
            return response()->json(['success' => true, 'cart_count' => $cartCount]);
        }

        return back()->with('cart_open', true)->with('success', 'Item added to cart!');
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'quantity' => 'required|integer|min:1'
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
            'key' => 'required|string'
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
     * Remove item from cart by Variant ID (used in checkout page)
     */
    public function removeByVariant(Request $request, $variantId)
    {
        $cart = $this->getCart();

        foreach ($cart as $key => $item) {
            if ($item['variant_id'] == $variantId) {
                unset($cart[$key]);
                $cart = array_values($cart); // Re-index
                break;
            }
        }

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

    /**
     * Add item to cart via AJAX
     */
    public function addAjax(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:item_variants,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $variantId = $request->variant_id;
        $quantity = $request->quantity;
        $cart = $this->getCart();

        // Check if variant exists and has enough stock
        $variant = ItemVariant::with('item')->find($variantId);
        if (!$variant || $variant->stock_quantity < $quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough stock available.'
            ], 400);
        }

        // Check if item already exists in cart
        $found = false;
        foreach ($cart as $key => $item) {
            if ($item['variant_id'] == $variantId) {
                $newQuantity = $item['quantity'] + $quantity;
                if ($newQuantity > $variant->stock_quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot add more than available stock.'
                    ], 400);
                }
                $cart[$key]['quantity'] = $newQuantity;
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

        // Calculate new cart count
        $cartCount = 0;
        foreach ($cart as $item) {
            $cartCount += $item['quantity'];
        }

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart!',
            'cart_count' => $cartCount,
            'item_name' => $variant->item->name
        ]);
    }

    /**
     * Get cart count via AJAX
     */
    public function getCountAjax()
    {
        $cartCount = $this->getCount();
        return response()->json([
            'cart_count' => $cartCount
        ]);
    }

    /**
     * Get cart items via AJAX for drawer
     */
    public function getItemsAjax()
    {
        $cart = $this->getCart();
        $items = [];
        $total = 0;

        foreach ($cart as $key => $item) {
            $variant = ItemVariant::with('item')->find($item['variant_id']);
            if ($variant && $variant->item) {
                $items[] = [
                    'key' => $key,
                    'variant_id' => $item['variant_id'],
                    'name' => $variant->item->name,
                    'variant_name' => $variant->variant_name ?? '',
                    'price' => $variant->price,
                    'quantity' => $item['quantity'],
                    'image' => $variant->item->image ? asset('storage/' . $variant->item->image) : asset('logo.png')
                ];
                $total += $variant->price * $item['quantity'];
            }
        }

        $cartCount = $this->getCount();

        return response()->json([
            'items' => $items,
            'total' => $total,
            'cart_count' => $cartCount
        ]);
    }

    /**
     * Update cart item quantity via AJAX
     */
    public function updateAjax(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'quantity' => 'required|integer|min:1'
        ]);

        $key = $request->key;
        $quantity = $request->quantity;
        $cart = $this->getCart();

        if (!isset($cart[$key])) {
            return response()->json(['success' => false, 'message' => 'Item not found in cart.'], 400);
        }

        $variant = ItemVariant::find($cart[$key]['variant_id']);
        if (!$variant || $variant->stock_quantity < $quantity) {
            return response()->json(['success' => false, 'message' => 'Not enough stock available.'], 400);
        }

        $cart[$key]['quantity'] = $quantity;
        $this->saveCart($cart);

        return response()->json(['success' => true, 'message' => 'Cart updated.']);
    }

    /**
     * Remove item from cart via AJAX
     */
    public function removeAjax(Request $request)
    {
        $request->validate([
            'key' => 'required|integer'
        ]);

        $key = $request->key;
        $cart = $this->getCart();

        if (!isset($cart[$key])) {
            return response()->json(['success' => false, 'message' => 'Item not found in cart.'], 400);
        }

        unset($cart[$key]);
        $this->saveCart($cart);

        return response()->json(['success' => true, 'message' => 'Item removed.']);
    }

    /**
     * Add entire bundle to cart (all items inside)
     */
    public function addBundle($bundleId)
    {
        $bundle = \App\Models\Bundle::with(['bundleItems.item.variants'])->findOrFail($bundleId);

        // Calculate original total of the bundle
        $originalTotal = 0;
        foreach ($bundle->bundleItems as $bundleItem) {
            $item = $bundleItem->item;
            $variant = $item ? ($item->variants->firstWhere('stock_quantity', '>', 0) ?? $item->variants->first()) : null;
            if ($variant) {
                $originalTotal += $variant->price * ($bundleItem->quantity ?? 1);
            }
        }

        // Discount ratio
        $ratio = $originalTotal > 0 ? $bundle->bundle_price / $originalTotal : 1;

        $cart = $this->getCart();
        $addedCount = 0;
        $errors = [];

        foreach ($bundle->bundleItems as $bundleItem) {
            $item = $bundleItem->item;
            if (!$item) continue;

            $variant = $item->variants->firstWhere('stock_quantity', '>', 0)
                       ?? $item->variants->first();

            if (!$variant) {
                $errors[] = $item->name . ' has no variants.';
                continue;
            }

            $qty = $bundleItem->quantity ?? 1;
            // Use unique key for bundle items so they don't merge with regular items
            $key = 'bundle_' . $bundle->id . '_variant_' . $variant->id;

            // Proportional price
            $customPrice = round($variant->price * $ratio);

            if (isset($cart[$key])) {
                $cart[$key]['quantity'] += $qty;
                $cart[$key]['bundle_id'] = $bundle->id;
                $cart[$key]['custom_price'] = $customPrice;
            } else {
                $cart[$key] = [
                    'variant_id' => $variant->id,
                    'quantity'   => $qty,
                    'bundle_id'  => $bundle->id,
                    'custom_price' => $customPrice,
                ];
            }
            $addedCount++;
        }

        $this->saveCart($cart);

        if ($addedCount > 0) {
            return redirect()->route('cart.index')->with('success', '"' . $bundle->name . '" bundle items added to cart!');
        }

        return back()->with('error', 'Could not add bundle items to cart.');
    }
}