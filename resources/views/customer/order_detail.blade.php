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

        @if($order->status === 'pending' && $order->payment_status !== 'paid')
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
                            <p class="item-price">{{ number_format($item->unit_price) }} Ks each</p>
                            
                            @if($order->status === 'completed')
                                @php
                                    $hasReviewed = \App\Models\Review::where('user_id', Auth::id())
                                                                     ->where('item_id', $item->item->id)
                                                                     ->exists();
                                @endphp
                                @if(!$hasReviewed)
                                    <button type="button" class="btn-write-review" onclick="openReviewModal({{ $item->item->id }}, '{{ addslashes($item->item->name) }}')" style="margin-top: 0.5rem; padding: 0.4rem 0.8rem; background: var(--primary); color: var(--secondary); border: none; border-radius: 4px; font-weight: 600; cursor: pointer; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 5px;">
                                        <i class="fa-regular fa-star"></i> Write a Review
                                    </button>
                                @else
                                    <span style="font-size: 0.8rem; color: #008080; display: inline-block; margin-top: 0.5rem; font-weight: 600;">
                                        <i class="fa-solid fa-circle-check"></i> Reviewed
                                    </span>
                                @endif
                            @endif
                        </div>
                        <div class="item-total">
                            {{ number_format($item->total_price) }} Ks
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

<!-- Review Modal -->
<div id="reviewModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center; padding: 1rem;">
    <div style="background: white; max-width: 500px; width: 100%; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.15); overflow: hidden; position: relative;">
        <!-- Header -->
        <div style="background: var(--secondary); color: white; padding: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 1.2rem;">Write a Product Review</h3>
            <button type="button" onclick="closeReviewModal()" style="background: none; border: none; color: white; font-size: 1.3rem; cursor: pointer;"><i class="fa-solid fa-times"></i></button>
        </div>
        <!-- Form -->
        <form action="{{ route('profile.add-review') }}" method="POST" style="padding: 1.5rem; display: flex; flex-direction: column; gap: 1.25rem;">
            @csrf
            <input type="hidden" name="item_id" id="modal_item_id">
            
            <div>
                <label style="font-weight: 600; color: var(--secondary); display: block; margin-bottom: 0.5rem;">Product Name</label>
                <div id="modal_product_name" style="font-weight: 800; color: #4a5568; background: #f7fafc; padding: 0.75rem; border-radius: 6px; border: 1px dashed #cbd5e0;"></div>
            </div>

            <!-- Star Selection -->
            <div>
                <label style="font-weight: 600; color: var(--secondary); display: block; margin-bottom: 0.5rem;">Your Rating</label>
                <div style="display: flex; gap: 0.75rem; font-size: 2rem; color: #FFC107; cursor: pointer;">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fa-regular fa-star star-btn" data-value="{{ $i }}" onclick="selectStars({{ $i }})" onmouseover="hoverStars({{ $i }})" onmouseout="resetStars()"></i>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="modal_rating" value="" required>
            </div>

            <!-- Comment Textarea -->
            <div>
                <label style="font-weight: 600; color: var(--secondary); display: block; margin-bottom: 0.5rem;">Your Review</label>
                <textarea name="comment" rows="4" placeholder="Write your review here. What did you like or dislike? How is the quality?" required style="width: 100%; border: 1px solid #cbd5e0; border-radius: 8px; padding: 0.75rem; box-sizing: border-box; font-family: inherit; font-size: 0.95rem; resize: none;"></textarea>
            </div>

            <button type="submit" style="background: var(--secondary); color: white; border: none; border-radius: 8px; padding: 0.75rem; font-weight: 700; cursor: pointer; transition: background 0.3s; font-size: 1rem;">Submit Review</button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let selectedRating = 0;

    function openReviewModal(itemId, productName) {
        document.getElementById('modal_item_id').value = itemId;
        document.getElementById('modal_product_name').innerText = productName;
        document.getElementById('reviewModal').style.display = 'flex';
        selectStars(0); // Reset stars
    }

    function closeReviewModal() {
        document.getElementById('reviewModal').style.display = 'none';
    }

    function selectStars(value) {
        selectedRating = value;
        document.getElementById('modal_rating').value = value;
        updateStarsDisplay(value);
    }

    function hoverStars(value) {
        updateStarsDisplay(value);
    }

    function resetStars() {
        updateStarsDisplay(selectedRating);
    }

    function updateStarsDisplay(value) {
        const stars = document.querySelectorAll('.star-btn');
        stars.forEach(star => {
            const starValue = parseInt(star.getAttribute('data-value'));
            if (starValue <= value) {
                star.className = 'fa-solid fa-star star-btn';
            } else {
                star.className = 'fa-regular fa-star star-btn';
            }
        });
    }
</script>
@endpush