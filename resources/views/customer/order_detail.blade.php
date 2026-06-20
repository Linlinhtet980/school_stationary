@extends('layouts.customer')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/customer/views/order_detail.css') }}">
@endpush


@section('title', 'Order Details - Campus Supply')

@section('content')
<div class="page-container">
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a> / 
        <a href="{{ route('profile.orders') }}">My Orders</a> / 
        Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    <!-- Order Status Card -->
    <div class="status-card">
        <div class="status-header">
            <h1>Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h1>
            <div class="status-badges">
                <span class="status-badge status-{{ $order->status }}">
                    {{ ucfirst($order->status) }}
                </span>
                <span class="payment-badge payment-{{ $order->payment_status }}">
                    {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                </span>
            </div>
        </div>
        <div class="status-info">
            <div class="info-item">
                <span class="info-label">Order Date:</span>
                <span class="info-value">{{ $order->created_at->format('F d, Y - g:i A') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Payment Method:</span>
                <span class="info-value">{{ ucfirst($order->payment->payment_method ?? 'N/A') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Total Amount:</span>
                <span class="info-value total">{{ number_format($order->total_amount) }} Ks</span>
            </div>
        </div>

        @if($order->status === 'pending')
            <div class="status-actions">
                <form action="{{ route('profile.order-cancel', $order->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-cancel" 
                            onclick="return confirm('Are you sure you want to cancel this order?')">
                        Cancel Order
                    </button>
                </form>
            </div>
        @endif
    </div>

    <div class="details-grid">
        <!-- Order Items -->
        <div class="items-card">
            <h2>Order Items</h2>
            <div class="items-list">
                @foreach($order->items as $item)
                    <div class="order-item">
                        <div class="item-image">
                            @if($item->item->image)
                                <img src="{{ asset('storage/' . $item->item->image) }}" alt="{{ $item->item->name }}">
                            @else
                                <div class="no-image">No Image</div>
                            @endif
                        </div>
                        <div class="item-details">
                            <h3>{{ $item->item->name }}</h3>
                            <p class="item-meta">Quantity: {{ $item->quantity }}</p>
                            <p class="item-price">{{ number_format($item->price) }} Ks each</p>
                        </div>
                        <div class="item-total">
                            {{ number_format($item->price * $item->quantity) }} Ks
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Shipping & Payment Info -->
        <div class="info-card">
            <!-- Shipping Information -->
            <div class="info-section">
                <h2>Shipping Information</h2>
                <div class="shipping-details">
                    @if($order->shipping_address)
                        <div class="detail-row">
                            <span class="label">Address:</span>
                            <span class="value">{{ $order->shipping_address }}</span>
                        </div>
                    @endif
                    @if($order->shipping_city)
                        <div class="detail-row">
                            <span class="label">City:</span>
                            <span class="value">{{ $order->shipping_city }}</span>
                        </div>
                    @endif
                    @if($order->shipping_phone)
                        <div class="detail-row">
                            <span class="label">Phone:</span>
                            <span class="value">{{ $order->shipping_phone }}</span>
                        </div>
                    @endif
                    @if($order->bus_gate)
                        <div class="detail-row">
                            <span class="label">Bus Gate:</span>
                            <span class="value">{{ $order->bus_gate }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Information -->
            <div class="info-section">
                <h2>Payment Information</h2>
                <div class="payment-details">
                    <div class="detail-row">
                        <span class="label">Method:</span>
                        <span class="value">{{ ucfirst($order->payment->payment_method ?? 'N/A') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Status:</span>
                        <span class="value payment-{{ $order->payment_status }}">
                            {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                        </span>
                    </div>
                    @if($order->payment->transaction_id)
                        <div class="detail-row">
                            <span class="label">Transaction ID:</span>
                            <span class="value">{{ $order->payment->transaction_id }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Summary -->
            <div class="info-section summary">
                <h2>Order Summary</h2>
                <div class="summary-details">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>{{ number_format($order->total_amount) }} Ks</span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span>Included</span>
                    </div>
                    <div class="summary-divider"></div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span>{{ number_format($order->total_amount) }} Ks</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="back-button">
        <a href="{{ route('profile.orders') }}" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Back to Orders
        </a>
    </div>
</div>


@endsection