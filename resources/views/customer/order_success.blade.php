@extends('layouts.customer')

@section('title', 'Order Success - Campus Supply')

@section('content')
<div class="page-container">
    <div class="success-container">
        <div class="success-icon">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        
        <h1 class="success-title">Order Placed Successfully!</h1>
        <p class="success-message">Thank you for your order. We'll send you a confirmation email shortly.</p>
        
        <div class="order-details-card">
            <h2>Order Details</h2>
            
            <div class="detail-row">
                <span class="detail-label">Order Number:</span>
                <span class="detail-value">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Order Date:</span>
                <span class="detail-value">{{ $order->created_at->format('F d, Y - g:i A') }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Order Status:</span>
                <span class="detail-value status-{{ $order->status }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Payment Status:</span>
                <span class="detail-value payment-{{ $order->payment_status }}">
                    {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                </span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Payment Method:</span>
                <span class="detail-value">{{ ucfirst($order->payment->payment_method ?? 'N/A') }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Total Amount:</span>
                <span class="detail-value total">{{ number_format($order->total_amount) }} Ks</span>
            </div>

            <div class="shipping-info">
                <h3>Shipping Information</h3>
                <div class="shipping-details">
                    <p><strong>Address:</strong> {{ $order->shipping_address ?? 'N/A' }}</p>
                    <p><strong>City:</strong> {{ $order->shipping_city ?? 'N/A' }}</p>
                    <p><strong>Phone:</strong> {{ $order->shipping_phone ?? 'N/A' }}</p>
                    @if($order->bus_gate)
                        <p><strong>Bus Gate:</strong> {{ $order->bus_gate }}</p>
                    @endif
                </div>
            </div>

            @if($order->payment_status === 'pending_verification')
                <div class="payment-pending-notice">
                    <i class="fa-solid fa-clock"></i>
                    <div>
                        <strong>Payment Verification Pending</strong>
                        <p>We're verifying your payment. This usually takes 1-2 business hours.</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="order-items-card">
            <h2>Order Items</h2>
            <div class="items-list">
                @foreach($order->items as $item)
                    <div class="order-item">
                        <div class="item-info">
                            <h4>{{ $item->item->name }}</h4>
                            <p>Quantity: {{ $item->quantity }}</p>
                            <p>Price: {{ number_format($item->price) }} Ks each</p>
                        </div>
                        <div class="item-total">
                            {{ number_format($item->price * $item->quantity) }} Ks
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="action-buttons">
            <a href="{{ route('home') }}" class="btn-continue-shopping">
                <i class="fa-solid fa-arrow-left"></i> Continue Shopping
            </a>
            <a href="{{ route('profile.orders') }}" class="btn-view-orders">
                <i class="fa-solid fa-receipt"></i> View Order History
            </a>
        </div>
    </div>
</div>

<style>
.page-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.success-container {
    text-align: center;
}

.success-icon {
    font-size: 5rem;
    color: #047857;
    margin-bottom: 1rem;
}

.success-title {
    font-size: 2rem;
    color: var(--secondary);
    margin-bottom: 0.5rem;
}

.success-message {
    color: #666;
    font-size: 1.1rem;
    margin-bottom: 2rem;
}

.order-details-card,
.order-items-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 1.5rem;
    text-align: left;
}

.order-details-card h2,
.order-items-card h2 {
    color: var(--secondary);
    font-size: 1.25rem;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid var(--primary);
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid #eee;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    color: #666;
    font-weight: 600;
}

.detail-value {
    color: var(--secondary);
    font-weight: 600;
}

.detail-value.total {
    font-size: 1.25rem;
    color: var(--secondary);
}

.detail-value.status-pending {
    color: #f59e0b;
}

.detail-value.status-processing {
    color: #3b82f6;
}

.detail-value.status-shipped {
    color: #8b5cf6;
}

.detail-value.status-completed {
    color: #047857;
}

.detail-value.status-cancelled {
    color: #dc3545;
}

.detail-value.payment-pending,
.detail-value.payment-pending_verification {
    color: #f59e0b;
}

.detail-value.payment-paid {
    color: #047857;
}

.detail-value.payment-failed {
    color: #dc3545;
}

.shipping-info {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid #eee;
}

.shipping-info h3 {
    color: var(--secondary);
    font-size: 1rem;
    margin-bottom: 0.75rem;
}

.shipping-details p {
    color: #333;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.payment-pending-notice {
    margin-top: 1.5rem;
    padding: 1rem;
    background: #fffbeb;
    border-left: 3px solid var(--primary);
    border-radius: 6px;
    display: flex;
    gap: 1rem;
    align-items: flex-start;
}

.payment-pending-notice i {
    color: var(--primary);
    font-size: 1.5rem;
}

.payment-pending-notice strong {
    color: var(--secondary);
    display: block;
}

.payment-pending-notice p {
    color: #666;
    font-size: 0.9rem;
    margin: 0.25rem 0 0 0;
}

.items-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.order-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.item-info h4 {
    color: var(--secondary);
    margin-bottom: 0.5rem;
}

.item-info p {
    color: #666;
    font-size: 0.85rem;
    margin-bottom: 0.25rem;
}

.item-total {
    font-weight: 700;
    color: var(--secondary);
    font-size: 1.1rem;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-continue-shopping,
.btn-view-orders {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s;
}

.btn-continue-shopping {
    background: #f5f5f5;
    color: #666;
    border: 1px solid #ddd;
}

.btn-continue-shopping:hover {
    background: #e9ecef;
    border-color: #ccc;
}

.btn-view-orders {
    background: var(--secondary);
    color: white;
}

.btn-view-orders:hover {
    background: #091a3a;
}
</style>
@endsection