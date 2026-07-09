@extends('layouts.customer')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/customer/views/orders.css') }}">
@endpush


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
                                onclick="event.preventDefault(); showConfirmModal('Are you sure you want to cancel this order?', () => this.closest('form').submit());">
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


@endsection