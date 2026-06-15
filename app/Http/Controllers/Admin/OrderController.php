<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('customer')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('sort')) {
            if ($request->sort === 'oldest') {
                $query->oldest('id');
            } else {
                $query->latest('id');
            }
        } else {
            $query->latest();
        }

        $orders = $query->paginate(5)->appends($request->except('page'));
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'orderItems.itemVariant.item']);
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled',
            'payment_status' => 'required|in:pending,paid,failed',
        ]);

        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status,
        ]);

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
                $msg = 'Payment verified and order status updated to processing.';
            } else {
                $order->payment->update(['status' => 'rejected']);
                $order->update(['payment_status' => 'failed']);
                $msg = 'Payment rejected.';
            }

            return redirect()->route('admin.orders.show', $order->id)->with('success', $msg);
        }

        return redirect()->route('admin.orders.show', $order->id)->with('error', 'Payment details not found.');
    }
}
