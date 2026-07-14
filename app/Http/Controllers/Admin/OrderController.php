<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user.customer')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user.customer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('sort')) {
            if ($request->sort === 'newest') {
                $query->latest('id');
            } else {
                $query->oldest('id');
            }
        } else {
            $query->oldest();
        }

        $orders = $query->paginate(5)->appends($request->except('page'));
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user.customer', 'items.itemVariant.item']);
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled',
            'payment_status' => 'required|in:pending,paid,failed',
        ]);

        $oldStatus = $order->status;
        
        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status,
        ]);

        if ($oldStatus !== $request->status) {
            $order->user->notify(new \App\Notifications\OrderStatusChanged($order));
        }

        return redirect()->route('admin.orders.show', $order->id)
                         ->with('success', 'Order status updated successfully.');
    }

    public function verifyPayment(Request $request, Order $order)
    {
        $request->validate([
            'action' => 'required|in:verify,reject',
        ]);

        if ($order->payment) {
            if ($request->action === 'verify') {
                $order->payment->update(['status' => 'verified']);
                $order->update(['payment_status' => 'paid', 'status' => 'processing']);
                $order->user->notify(new \App\Notifications\OrderStatusChanged($order));
                $msg = 'Payment verified and order status updated to processing.';
            } else {
                $order->payment->update(['status' => 'rejected']);
                $order->update(['payment_status' => 'failed', 'status' => 'cancelled']);
                $order->user->notify(new \App\Notifications\OrderStatusChanged($order));
                $msg = 'Payment rejected and order cancelled.';
            }

            return redirect()->route('admin.orders.show', $order->id)->with('success', $msg);
        }

        return redirect()->route('admin.orders.show', $order->id)->with('error', 'Payment details not found.');
    }
}
