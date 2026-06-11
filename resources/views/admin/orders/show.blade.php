@extends('layouts.admin')

@section('title', 'Order Invoice')
@section('header_title', 'Order Invoice')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/orders.css') }}">
@endpush

@section('content')
<div class="card">
    <div class="card-header card-header-flex">
        <h2><i class="fa-solid fa-file-invoice"></i> Order #{{ $order->order_number }}</h2>
        <a href="{{ route('admin.orders.index') }}" class="btn-outline"><i class="fa-solid fa-arrow-left"></i> Back to Orders</a>
    </div>
    
    <div class="card-body">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="grid-layout">
            <!-- Left Side: Order Items -->
            <div>
                <h3 class="section-title">Items Ordered</h3>
                <table class="table">
                    <thead>
                        <tr class="table-header-row">
                            <th class="text-left">Item</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Qty</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $oItem)
                            <tr class="table-row-item">
                                <td>
                                    <div class="item-name">{{ $oItem->itemVariant->item->name ?? 'Unknown Item' }}</div>
                                    <div class="item-variant-info">
                                        Variant: {{ $oItem->itemVariant->unit_label }} x {{ $oItem->itemVariant->unit_qty }} 
                                        @if($oItem->itemVariant->color) | Color: {{ $oItem->itemVariant->color }} @endif
                                        @if($oItem->itemVariant->size) | Size: {{ $oItem->itemVariant->size }} @endif
                                    </div>
                                </td>
                                <td class="text-center">{{ number_format($oItem->unit_price, 2) }} Ks</td>
                                <td class="text-center">{{ $oItem->quantity }}</td>
                                <td class="text-right text-amount">{{ number_format($oItem->total_price, 2) }} Ks</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-footer">
                        <tr>
                            <td colspan="3" class="text-right grand-total-label">Grand Total:</td>
                            <td class="text-right grand-total-value">{{ number_format($order->total_amount, 2) }} Ks</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Right Side: Order Info & Status Update -->
            <div>
                <!-- Customer Info -->
                <div class="info-box mb-4">
                    <h4><i class="fa-solid fa-user"></i> Customer Info</h4>
                    @if($order->customer)
                        <p class="customer-name">{{ $order->customer->name }}</p>
                        <p><i class="fa-solid fa-phone"></i> {{ $order->customer->phone }}</p>
                        <p><i class="fa-solid fa-envelope"></i> {{ $order->customer->user->email ?? 'N/A' }}</p>
                        <a href="{{ route('admin.customers.show', $order->customer->id) }}" class="profile-link">View Full Profile &rarr;</a>
                    @else
                        <p class="error-text">Customer data not found.</p>
                    @endif
                </div>

                <!-- Shipping Info -->
                <div class="info-box mb-4">
                    <h4><i class="fa-solid fa-truck"></i> Shipping Address</h4>
                    <p>
                        {{ $order->shipping_address ?: 'No address provided.' }}
                    </p>
                </div>

                <!-- Status Update Form -->
                <div class="status-box">
                    <h4><i class="fa-solid fa-pen-to-square"></i> Update Status</h4>
                    
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group mb-3">
                            <label>Payment Method</label>
                            <input type="text" value="{{ $order->payment_method ?: 'N/A' }}" disabled class="input-disabled">
                        </div>

                        <div class="form-group mb-3">
                            <label>Payment Status</label>
                            <select name="payment_status">
                                <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label>Order Status</label>
                            <select name="status">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <button type="submit" class="btn-primary">
                            <i class="fa-solid fa-save"></i> Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
