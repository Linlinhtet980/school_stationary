<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ItemVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display customer's orders
     */
    public function index()
    {
        $orders = Order::with('items', 'payment')
                      ->where('user_id', Auth::id())
                      ->latest()
                      ->paginate(10);

        return view('customer.orders', compact('orders'));
    }

    /**
     * Display specific order details
     */
    public function show($id)
    {
        $order = Order::with(['items.itemVariant.item', 'payment'])
                     ->where('user_id', Auth::id())
                     ->findOrFail($id);

        return view('customer.order_detail', compact('order'));
    }

    /**
     * Cancel order (if possible)
     */
    public function cancel(Request $request, $id)
    {
        $order = Order::with('items')
                     ->where('user_id', Auth::id())
                     ->where('id', $id)
                     ->firstOrFail();

        // Only allow cancellation if order is pending
        if ($order->status !== 'pending') {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        $order->update(['status' => 'cancelled']);

        // Order cancel ဖြစ်သည့်အခါ stock ကို ပြန်ထည့် (restore)
        foreach ($order->items as $orderItem) {
            ItemVariant::where('id', $orderItem->item_variant_id)
                       ->increment('stock_quantity', $orderItem->quantity);
        }

        return back()->with('success', 'Order cancelled successfully. Stock has been restored.');
    }
}