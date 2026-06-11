@extends('layouts.admin')

@section('title', 'Orders')
@section('header_title', 'Orders Management')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/orders.css') }}">
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <h2><i class="fa-solid fa-receipt"></i> Recent Orders</h2>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <table class="data-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Total Amount</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td class="text-order-number">{{ $order->order_number }}</td>
                        <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                        <td>
                            @if($order->customer)
                                <a href="{{ route('admin.customers.show', $order->customer->id) }}" class="text-customer-name">
                                    {{ $order->customer->name }}
                                </a>
                            @else
                                <span class="text-unknown">Unknown</span>
                            @endif
                        </td>
                        <td class="text-amount">{{ number_format($order->total_amount, 2) }} Ks</td>
                        <td>
                            <div class="payment-info">
                                <span class="payment-method">{{ $order->payment_method ?: 'N/A' }}</span>
                                <span class="badge badge-pay-{{ $order->payment_status }}">{{ $order->payment_status }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-status-{{ $order->status }}">{{ $order->status }}</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-icon btn-view" title="View Details">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
