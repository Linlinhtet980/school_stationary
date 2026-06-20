@extends('layouts.customer')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/customer/views/order_success.css') }}">
@endpush

@section('title', 'Order Confirmed - Campus Supply')



@section('content')
<div class="success-page">
    <div class="success-card">
        <div class="success-header">
            <div class="icon-circle"><i class="fa-solid fa-check"></i></div>
            <h1>Order Confirmed!</h1>
            <p>Thank you for your purchase. We will process it shortly.</p>
        </div>
        <div class="order-details">
            <div class="order-meta">
                <div class="meta-box">
                    <span>Order ID</span>
                    <strong>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong>
                </div>
                <div class="meta-box right">
                    <span>Date</span>
                    <strong>{{ $order->created_at->format('F d, Y') }}</strong>
                </div>
            </div>

            <h3 class="inline-style-99">Order Summary</h3>
            <div class="order-items-list">
                @foreach($order->items as $item)
                <div class="order-item-row">
                    <span>{{ $item->item->item_name ?? 'Item' }} (x{{ $item->quantity }})</span>
                    <span>{{ number_format($item->price * $item->quantity) }} Ks</span>
                </div>
                @endforeach
            </div>

            @php
                $shipping = $order->total_amount - $order->items->sum(fn($i) => $i->price * $i->quantity);
                $subtotal = $order->items->sum(fn($i) => $i->price * $i->quantity);
            @endphp

            <div class="summary-row">
                <span>Subtotal</span>
                <span>{{ number_format($subtotal) }} Ks</span>
            </div>
            <div class="summary-row">
                <span>Shipping</span>
                <span>{{ number_format(max(0, $shipping)) }} Ks</span>
            </div>
            <div class="summary-row total">
                <span>Total Paid</span>
                <span>{{ number_format($order->total_amount) }} Ks</span>
            </div>

            @if($order->payment)
            <div class="payment-status-row">
                <i class="fa-solid fa-clock"></i>
                <div>
                    <strong>Payment Status: {{ ucfirst($order->payment->status) }}</strong><br>
                    <small>Method: {{ strtoupper($order->payment->payment_method) }}
                    @if($order->payment->payment_method !== 'cod')
                        — We will verify your payment screenshot within 24 hours.
                    @endif
                    </small>
                </div>
            </div>
            @endif

            <a href="{{ route('home') }}" class="btn-home"><i class="fa-solid fa-house inline-style-100" ></i> Return to Homepage</a>
            <a href="{{ route('profile.index') }}#orders" class="btn-orders"><i class="fa-solid fa-box inline-style-101" ></i> View My Orders</a>
        </div>
    </div>
</div>
@endsection