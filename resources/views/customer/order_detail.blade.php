@extends('layouts.customer')

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

<style>
.page-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.breadcrumb {
    color: #A0AEC0;
    font-size: 0.9rem;
    margin-bottom: 1.5rem;
}

.breadcrumb a {
    color: var(--primary);
    text-decoration: none;
}

.alert {
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.status-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
}

.status-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #eee;
}

.status-header h1 {
    font-size: 1.5rem;
    color: var(--secondary);
    margin: 0;
}

.status-badges {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.status-badge,
.payment-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.status-pending,
.payment-badge.payment-pending,
.payment-badge.payment-pending_verification {
    background: #fffbeb;
    color: #b45309;
}

.status-badge.status-processing {
    background: #dbeafe;
    color: #1e40af;
}

.status-badge.status-shipped {
    background: #ede9fe;
    color: #6d28d9;
}

.status-badge.status-completed,
.payment-badge.payment-paid {
    background: #d1fae5;
    color: #047857;
}

.status-badge.status-cancelled,
.payment-badge.payment-failed {
    background: #fee2e2;
    color: #b91c1c;
}

.status-info {
    display: grid;
    gap: 1rem;
    margin-bottom: 1rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
}

.info-label {
    color: #666;
    font-weight: 600;
}

.info-value {
    color: var(--secondary);
    font-weight: 600;
}

.info-value.total {
    font-size: 1.25rem;
    color: var(--secondary);
}

.status-actions {
    padding-top: 1rem;
    border-top: 1px solid #eee;
}

.btn-cancel {
    padding: 0.75rem 1.5rem;
    background: white;
    color: #dc3545;
    border: 1px solid #dc3545;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-cancel:hover {
    background: #dc3545;
    color: white;
}

.details-grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 2rem;
    margin-bottom: 2rem;
}

@media (max-width: 768px) {
    .details-grid {
        grid-template-columns: 1fr;
    }
}

.items-card,
.info-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.items-card h2,
.info-card h2 {
    color: var(--secondary);
    font-size: 1.25rem;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid var(--primary);
}

.items-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.order-item {
    display: grid;
    grid-template-columns: 80px 1fr auto;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    align-items: center;
}

.item-image img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 6px;
}

.item-image .no-image {
    width: 80px;
    height: 80px;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999;
    border-radius: 6px;
}

.item-details h3 {
    color: var(--secondary);
    margin-bottom: 0.5rem;
    font-size: 1rem;
}

.item-meta,
.item-price {
    color: #666;
    font-size: 0.85rem;
    margin: 0.25rem 0;
}

.item-total {
    font-weight: 700;
    color: var(--secondary);
    font-size: 1.1rem;
}

.info-section {
    margin-bottom: 1.5rem;
}

.info-section:last-child {
    margin-bottom: 0;
}

.info-section h2 {
    color: var(--secondary);
    font-size: 1rem;
    margin-bottom: 0.75rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #eee;
}

.info-section.summary h2 {
    margin-top: 1.5rem;
}

.shipping-details,
.payment-details,
.summary-details {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.detail-row {
    display: flex;
    justify-content: space-between;
}

.detail-row .label {
    color: #666;
    font-weight: 600;
    font-size: 0.9rem;
}

.detail-row .value {
    color: var(--secondary);
    font-weight: 600;
    font-size: 0.9rem;
}

.detail-row .value.payment-pending,
.detail-row .value.payment-pending_verification {
    color: #b45309;
}

.detail-row .value.payment-paid {
    color: #047857;
}

.detail-row .value.payment-failed {
    color: #b91c1c;
}

.summary-row {
    display: flex;
    justify-content: space-between;
}

.summary-row.total {
    font-weight: 700;
    font-size: 1.1rem;
    color: var(--secondary);
}

.summary-divider {
    border-top: 1px solid #eee;
    margin: 0.5rem 0;
}

.back-button {
    text-align: center;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: #f5f5f5;
    color: #666;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-back:hover {
    background: #e9ecef;
}
</style>
@endsection