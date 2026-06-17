@extends('layouts.customer')

@section('title', 'My Orders - Campus Supply')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>My Orders</h1>
        <p>Track and manage your orders</p>
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

    @forelse($orders as $order)
        <div class="order-card">
            <div class="order-header">
                <div class="order-info">
                    <div class="order-number">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
                    <div class="order-date">{{ $order->created_at->format('F d, Y - g:i A') }}</div>
                </div>
                <div class="order-status">
                    <span class="status-badge status-{{ $order->status }}">
                        {{ ucfirst($order->status) }}
                    </span>
                    <span class="payment-badge payment-{{ $order->payment_status }}">
                        {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                    </span>
                </div>
            </div>

            <div class="order-body">
                <div class="order-items-preview">
                    @foreach($order->items->take(3) as $item)
                        <div class="order-item-preview">
                            @if($item->item->image)
                                <img src="{{ asset('storage/' . $item->item->image) }}" alt="{{ $item->item->name }}">
                            @else
                                <div class="no-image">No Image</div>
                            @endif
                            <div class="item-count">{{ $item->quantity }}x</div>
                        </div>
                    @endforeach
                    @if($order->items->count() > 3)
                        <div class="more-items">+{{ $order->items->count() - 3 }} more</div>
                    @endif
                </div>

                <div class="order-totals">
                    <div class="total-row">
                        <span>Total:</span>
                        <span>{{ number_format($order->total_amount) }} Ks</span>
                    </div>
                </div>
            </div>

            <div class="order-footer">
                <a href="{{ route('profile.order-detail', $order->id) }}" class="btn-view-order">
                    View Details
                </a>
                @if($order->status === 'pending')
                    <form action="{{ route('profile.order-cancel', $order->id) }}" method="POST" class="cancel-form">
                        @csrf
                        <button type="submit" class="btn-cancel-order" 
                                onclick="return confirm('Are you sure you want to cancel this order?')">
                            Cancel Order
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="no-orders">
            <i class="fa-solid fa-box-open"></i>
            <h3>No orders yet</h3>
            <p>Start shopping to see your orders here</p>
            <a href="{{ route('shop.index') }}" class="btn-start-shopping">Start Shopping</a>
        </div>
    @endforelse

    @if($orders->hasPages())
        <div class="pagination">
            @if($orders->onFirstPage())
                <span class="page-btn disabled"><i class="fa-solid fa-angle-left"></i></span>
            @else
                <a href="{{ $orders->previousPageUrl() }}" class="page-btn">
                    <i class="fa-solid fa-angle-left"></i>
                </a>
            @endif

            @foreach($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                @if($page == $orders->currentPage())
                    <span class="page-btn active">{{ $page }}</span>
                @elseif($page == 1 || $page == $orders->lastPage() || ($page >= $orders->currentPage() - 1 && $page <= $orders->currentPage() + 1))
                    <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                @endif
            @endforeach

            @if($orders->hasMorePages())
                <a href="{{ $orders->nextPageUrl() }}" class="page-btn">
                    <i class="fa-solid fa-angle-right"></i>
                </a>
            @else
                <span class="page-btn disabled"><i class="fa-solid fa-angle-right"></i></span>
            @endif
        </div>
    @endif
</div>

<style>
.page-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.page-header {
    text-align: center;
    margin-bottom: 2rem;
}

.page-header h1 {
    font-size: 2rem;
    color: var(--secondary);
    margin-bottom: 0.5rem;
}

.page-header p {
    color: #666;
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

.order-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #eee;
}

.order-info .order-number {
    font-weight: 700;
    color: var(--secondary);
    font-size: 1.1rem;
}

.order-info .order-date {
    color: #666;
    font-size: 0.9rem;
    margin-top: 0.25rem;
}

.order-status {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.status-badge,
.payment-badge {
    padding: 0.35rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
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

.order-body {
    display: flex;
    justify-content: space-between;
    padding: 1.5rem;
}

.order-items-preview {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.order-item-preview {
    position: relative;
    width: 60px;
    height: 60px;
}

.order-item-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 6px;
}

.order-item-preview .no-image {
    width: 100%;
    height: 100%;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999;
    font-size: 0.7rem;
    border-radius: 6px;
}

.item-count {
    position: absolute;
    bottom: -5px;
    right: -5px;
    background: var(--primary);
    color: var(--secondary);
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 700;
}

.more-items {
    color: #666;
    font-size: 0.9rem;
}

.order-totals .total-row {
    display: flex;
    justify-content: space-between;
    gap: 2rem;
    font-weight: 700;
    color: var(--secondary);
}

.order-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    background: #f8f9fa;
    border-top: 1px solid #eee;
}

.btn-view-order {
    padding: 0.75rem 1.5rem;
    background: var(--secondary);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: background 0.3s;
}

.btn-view-order:hover {
    background: #091a3a;
}

.cancel-form {
    display: inline;
}

.btn-cancel-order {
    padding: 0.75rem 1.5rem;
    background: white;
    color: #dc3545;
    border: 1px solid #dc3545;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-cancel-order:hover {
    background: #dc3545;
    color: white;
}

.no-orders {
    text-align: center;
    padding: 4rem 2rem;
    color: #999;
}

.no-orders i {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.no-orders h3 {
    color: var(--secondary);
    margin-bottom: 0.5rem;
}

.btn-start-shopping {
    display: inline-block;
    margin-top: 1rem;
    padding: 0.75rem 1.5rem;
    background: var(--secondary);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
}

.pagination {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-top: 2rem;
}

.page-btn {
    min-width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #ddd;
    border-radius: 8px;
    text-decoration: none;
    color: var(--secondary);
    font-weight: 600;
    transition: all 0.3s;
}

.page-btn:hover:not(.disabled):not(.active) {
    border-color: var(--primary);
    background: #fffbeb;
}

.page-btn.active {
    background: var(--secondary);
    color: white;
    border-color: var(--secondary);
}

.page-btn.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

@media (max-width: 768px) {
    .order-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .order-body {
        flex-direction: column;
        gap: 1rem;
    }
    
    .order-footer {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>
@endsection