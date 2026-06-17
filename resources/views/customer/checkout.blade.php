@extends('layouts.customer')

@section('title', 'Checkout - Campus Supply')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/customer/checkout.css') }}">
@endpush

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>Checkout</h1>
        <p>Complete your order</p>
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

    <form action="{{ route('checkout.process') }}" method="POST" enctype="multipart/form-data" id="checkoutForm">
        @csrf
        
        <div class="checkout-container">
            <!-- Left Column: Shipping & Payment -->
            <div class="checkout-main">
                
                <!-- Shipping Address -->
                <div class="checkout-section">
                    <h2 class="section-title">
                        <i class="fa-solid fa-location-dot"></i> Shipping Address
                    </h2>
                    
                    @if($addresses->count() > 0)
                        <div class="address-list">
                            @foreach($addresses as $address)
                                <div class="address-card {{ $address->is_default ? 'selected' : '' }}">
                                    <label class="address-radio">
                                        <input type="radio" 
                                               name="address_id" 
                                               value="{{ $address->id }}" 
                                               {{ $address->is_default ? 'checked' : '' }}
                                               required>
                                        <div class="address-content">
                                            @if($address->label)
                                                <div class="address-label">{{ $address->label }}</div>
                                            @endif
                                            <div class="address-line">{{ $address->address_line }}</div>
                                            <div class="address-city">{{ $address->city }}</div>
                                            <div class="address-phone">
                                                <i class="fa-solid fa-phone"></i> {{ $address->phone }}
                                            </div>
                                            @if($address->is_default)
                                                <span class="default-badge">Default</span>
                                            @endif
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        
                        <button type="button" class="btn-add-address" onclick="showAddressForm()">
                            <i class="fa-solid fa-plus"></i> Add New Address
                        </button>
                    @else
                        <div class="no-address">
                            <p>No saved addresses found. Please add a shipping address.</p>
                        </div>
                    @endif

                    <!-- Add Address Form (Hidden by default) -->
                    <div class="add-address-form" id="addAddressForm" hidden>
                        <h3>Add New Address</h3>
                        <form action="{{ route('checkout.add-address') }}" method="POST" class="address-form">
                            @csrf
                            <div class="form-group">
                                <label>Label (Optional)</label>
                                <input type="text" name="label" placeholder="e.g., Home, Office">
                            </div>
                            <div class="form-group">
                                <label>Address Line *</label>
                                <textarea name="address_line" rows="2" required placeholder="Full address"></textarea>
                            </div>
                            <div class="form-group">
                                <label>City *</label>
                                <input type="text" name="city" required placeholder="City">
                            </div>
                            <div class="form-group">
                                <label>Phone *</label>
                                <input type="text" name="phone" required placeholder="09xxxxxxxxx">
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="is_default"> Set as default address
                                </label>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn-save">Save Address</button>
                                <button type="button" class="btn-cancel" onclick="hideAddressForm()">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="checkout-section">
                    <h2 class="section-title">
                        <i class="fa-solid fa-phone"></i> Contact Information
                    </h2>
                    <div class="form-group">
                        <label>Phone Number *</label>
                        <input type="text" 
                               name="phone" 
                               class="form-input" 
                               required 
                               placeholder="09xxxxxxxxx"
                               pattern="^09[0-9]{7,9}$">
                        <small>For delivery contact</small>
                    </div>
                    <div class="form-group">
                        <label>Bus Gate (Optional)</label>
                        <input type="text" 
                               name="bus_gate" 
                               class="form-input" 
                               placeholder="e.g., Gate A, Near Building 5">
                        <small>Help our driver find you easily</small>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="checkout-section">
                    <h2 class="section-title">
                        <i class="fa-solid fa-credit-card"></i> Payment Method
                    </h2>
                    
                    <div class="payment-methods">
                        <div class="payment-option">
                            <label class="payment-radio">
                                <input type="radio" 
                                       name="payment_method" 
                                       value="cod" 
                                       checked
                                       onchange="togglePaymentFields()">
                                <div class="payment-content">
                                    <div class="payment-icon">
                                        <i class="fa-solid fa-money-bill-wave"></i>
                                    </div>
                                    <div class="payment-info">
                                        <div class="payment-name">Cash on Delivery</div>
                                        <div class="payment-desc">Pay when your order arrives</div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <div class="payment-option">
                            <label class="payment-radio">
                                <input type="radio" 
                                       name="payment_method" 
                                       value="kpay"
                                       onchange="togglePaymentFields()">
                                <div class="payment-content">
                                    <div class="payment-icon kpay">
                                        <span>KPay</span>
                                    </div>
                                    <div class="payment-info">
                                        <div class="payment-name">KBZPay</div>
                                        <div class="payment-desc">Pay with KBZPay app</div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <div class="payment-option">
                            <label class="payment-radio">
                                <input type="radio" 
                                       name="payment_method" 
                                       value="wave"
                                       onchange="togglePaymentFields()">
                                <div class="payment-content">
                                    <div class="payment-icon wave">
                                        <span>Wave</span>
                                    </div>
                                    <div class="payment-info">
                                        <div class="payment-name">Wave Money</div>
                                        <div class="payment-desc">Pay with Wave Money app</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Payment Slip Upload (Hidden by default) -->
                    <div class="payment-slip-section" id="paymentSlipSection" hidden>
                        <div class="form-group">
                            <label>Upload Payment Slip *</label>
                            <input type="file" 
                                   name="payment_slip" 
                                   id="paymentSlip"
                                   accept="image/jpeg,image/png,image/jpg"
                                   class="form-input">
                            <small>Please upload a clear screenshot of your payment receipt</small>
                            <div id="slipPreview" class="slip-preview"></div>
                        </div>
                        <div class="payment-instructions">
                            <h4>Payment Instructions:</h4>
                            <ol>
                                <li>Select your preferred payment method above</li>
                                <li>Transfer the total amount to our account:</li>
                                <li><strong>KBZPay:</strong> 09401234567</li>
                                <li><strong>Wave Money:</strong> 09407654321</li>
                                <li>Take a screenshot of the payment receipt</li>
                                <li>Upload the screenshot above</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Order Summary -->
            <div class="checkout-sidebar">
                <div class="order-summary">
                    <h2 class="section-title">Order Summary</h2>
                    
                    <div class="summary-items">
                        @foreach($cartItems as $item)
                            <div class="summary-item">
                                <div class="item-image">
                                    @if($item['variant']->item->image)
                                        <img src="{{ asset('storage/' . $item['variant']->item->image) }}" 
                                             alt="{{ $item['variant']->item->name }}">
                                    @else
                                        <div class="no-image">No Image</div>
                                    @endif
                                </div>
                                <div class="item-details">
                                    <div class="item-name">{{ $item['variant']->item->name }}</div>
                                    <div class="item-variant">
                                        {{ $item['variant']->unit_label }}
                                        @if($item['variant']->color) - {{ $item['variant']->color }} @endif
                                        @if($item['variant']->size) - {{ $item['variant']->size }} @endif
                                    </div>
                                    <div class="item-qty">x {{ $item['quantity'] }}</div>
                                </div>
                                <div class="item-price">{{ number_format($item['subtotal']) }} Ks</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="summary-totals">
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>{{ number_format($subtotal) }} Ks</span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping</span>
                            <span>{{ $shipping > 0 ? number_format($shipping) . ' Ks' : 'Free' }}</span>
                        </div>
                        @if($shipping === 0)
                            <div class="free-shipping-notice">
                                <i class="fa-solid fa-truck"></i> Free shipping applied!
                            </div>
                        @endif
                        <div class="summary-divider"></div>
                        <div class="summary-row total">
                            <span>Total</span>
                            <span>{{ number_format($total) }} Ks</span>
                        </div>
                    </div>

                    <div class="checkout-actions">
                        <button type="submit" class="btn-place-order">
                            Place Order
                        </button>
                        <p class="terms-text">
                            By placing this order, you agree to our Terms of Service and Privacy Policy
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>




<script>
function showAddressForm() {
    document.getElementById('addAddressForm').removeAttribute('hidden');
}

function hideAddressForm() {
    document.getElementById('addAddressForm').setAttribute('hidden', '');
}

function togglePaymentFields() {
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
    const slipSection = document.getElementById('paymentSlipSection');
    
    if (paymentMethod === 'kpay' || paymentMethod === 'wave') {
        slipSection.removeAttribute('hidden');
        document.getElementById('paymentSlip').required = true;
    } else {
        slipSection.setAttribute('hidden', '');
        document.getElementById('paymentSlip').required = false;
    }
}

// Payment slip preview
document.getElementById('paymentSlip').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('slipPreview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Payment Slip Preview">';
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
});

// Form validation
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
    const paymentSlip = document.getElementById('paymentSlip');
    
    if ((paymentMethod === 'kpay' || paymentMethod === 'wave') && !paymentSlip.files[0]) {
        e.preventDefault();
        alert('Please upload your payment slip');
        paymentSlip.focus();
    }
});
</script>
@endsection
