@extends('layouts.customer')

@section('title', 'My Cart - Campus Supply')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/customer/cart.css') }}">
@endpush

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>My Cart</h1>
        <p>Review your items before checkout</p>
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

    <div class="cart-container">
        <div class="cart-items">
            @forelse($cartItems as $cartItem)
                <div class="cart-item">
                    <div class="item-image">
                        @if($cartItem['item']['image'])
                            <img src="{{ asset('storage/' . $cartItem['item']['image']) }}" alt="{{ $cartItem['item']['name'] }}">
                        @else
                            <div class="no-image">No Image</div>
                        @endif
                    </div>
                    
                    <div class="item-details">
                        <div class="item-name">
                            <a href="{{ route('shop.show', $cartItem['item']['id']) }}">
                                {{ $cartItem['item']['name'] }}
                            </a>
                        </div>
                        
                        @if($cartItem['variant'])
                            <div class="item-variant">
                                {{ $cartItem['variant']['name'] ?? '' }}
                            </div>
                        @endif
                        
                        <div class="item-price">
                            @if($cartItem['variant'])
                                {{ number_format($cartItem['variant']['price']) }} Ks
                            @else
                                {{ number_format($cartItem['item']['price']) }} Ks
                            @endif
                        </div>
                    </div>
                    
                    <div class="item-quantity">
                        <div class="qty-selector">
                            <button type="button" class="qty-btn" onclick="updateQuantity({{ $cartItem['key'] }}, {{ $cartItem['quantity'] - 1 }})">-</button>
                            <input type="number" value="{{ $cartItem['quantity'] }}" class="qty-input" readonly>
                            <button type="button" class="qty-btn" onclick="updateQuantity({{ $cartItem['key'] }}, {{ $cartItem['quantity'] + 1 }})">+</button>
                        </div>
                        <form action="{{ route('cart.remove') }}" method="POST" class="remove-form">
                            @csrf
                            <input type="hidden" name="key" value="{{ $cartItem['key'] }}">
                            <button type="submit" class="btn-remove">Remove</button>
                        </form>
                    </div>
                    
                    <div class="item-total">
                        {{ number_format($cartItem['total']) }} Ks
                    </div>
                </div>
            @empty
                <div class="empty-cart">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <h3>Your cart is empty</h3>
                    <p>Add some items to get started</p>
                    <a href="{{ route('shop.index') }}" class="btn-continue-shopping">Continue Shopping</a>
                </div>
            @endforelse
        </div>

        @if($cartItems->count() > 0)
            <div class="cart-summary">
                <h2>Order Summary</h2>
                
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>{{ number_format($subtotal) }} Ks</span>
                </div>
                
                <div class="summary-row">
                    <span>Shipping</span>
                    <span>{{ $shipping > 0 ? number_format($shipping) . ' Ks' : 'Free' }}</span>
                </div>
                
                <div class="summary-row total">
                    <span>Total</span>
                    <span>{{ number_format($total) }} Ks</span>
                </div>
                
                <a href="{{ route('checkout.index') }}" class="btn-checkout">
                    Proceed to Checkout
                </a>
                
                <a href="{{ route('shop.index') }}" class="btn-continue">
                    Continue Shopping
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateQuantity(key, newQty) {
    if (newQty < 1) return;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route('cart.update') }}';
    
    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = document.querySelector('meta[name="csrf-token"]').content;
    form.appendChild(csrf);
    
    const keyInput = document.createElement('input');
    keyInput.type = 'hidden';
    keyInput.name = 'key';
    keyInput.value = key;
    form.appendChild(keyInput);
    
    const qtyInput = document.createElement('input');
    qtyInput.type = 'hidden';
    qtyInput.name = 'quantity';
    qtyInput.value = newQty;
    form.appendChild(qtyInput);
    
    document.body.appendChild(form);
    form.submit();
}
</script>
@endpush