@extends('layouts.customer')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/customer/views/checkout.css') }}">
@endpush

@section('title', 'Campus Supply - Checkout')

@section('content')

    {{-- Cart Remove Forms (checkout form အပြင်မှာ သီးသန့်) --}}
    @foreach($cartItems as $item)
        <form id="remove-form-{{ $item['variant']->id }}" action="{{ route('cart.remove-variant', $item['variant']->id) }}"
            method="POST" style="display:none">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

    {{-- Main Checkout Form --}}
    <form action="{{ route('checkout.process') }}" method="POST" enctype="multipart/form-data" id="checkoutForm">
        @csrf
        <div class="checkout-container">

            <!-- Left Column -->
            <div class="left-col">
                <a href="{{ route('shop.index') }}" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Continue Shopping
                </a>

                <h2 class="section-title">1. Your Cart</h2>

                @foreach($cartItems as $item)
                    <div class="cart-item">
                        <img src="{{ $item['variant']->item->images->first() ? asset('storage/' . $item['variant']->item->images->first()->image_path) : asset('images/placeholder.jpg') }}"
                            class="cart-item-img" alt="Item">
                        <div class="cart-item-info">
                            <div class="cart-item-title">{{ $item['variant']->item->name }}</div>
                            <div class="cart-item-desc">Variant:
                                {{ $item['variant']->color_name ?? $item['variant']->item_code }}</div>
                        </div>
                        <div class="qty-selector">
                            <button type="button" class="qty-btn" onclick="updateCheckoutQuantity('{{ $item['key'] }}', {{ $item['quantity'] - 1 }})">-</button>
                            <span class="qty-input" style="display:inline-block; padding-top: 3px;">{{ $item['quantity'] }}</span>
                            <button type="button" class="qty-btn" onclick="updateCheckoutQuantity('{{ $item['key'] }}', {{ $item['quantity'] + 1 }})">+</button>
                        </div>
                        <div class="cart-item-price">{{ number_format($item['subtotal']) }} Ks</div>
                        {{-- form မသုံးဘဲ JS နဲ့ submit --}}
                        <button type="button" class="checkout-btn-remove" onclick="removeItem({{ $item['variant']->id }})">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                @endforeach

                <h2 class="section-title inline-style-48">2. Shipping Information</h2>

                @if(count($addresses) > 0)
                    <div class="form-group inline-style-49">
                        <label>Select Saved Address</label>
                        <select id="savedAddressSelect" onchange="fillAddressForm()" class="inline-style-50">
                            <option value="">-- Enter New Address Below --</option>
                            @foreach($addresses as $address)
                                <option value="{{ $address->id }}" data-line="{{ $address->address_line }}"
                                    data-city="{{ $address->city }}" data-phone="{{ $address->phone }}">
                                    {{ $address->label ?? 'Saved Address' }} ({{ $address->address_line }}, {{ $address->city }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="full_name" value="{{ old('full_name', Auth::user()->name) }}"
                            placeholder="e.g. Kyaw Min" required>
                        @error('full_name') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" placeholder="09xxxxxxxxx"
                            pattern="09[0-9]{7,9}" required>
                        @error('phone') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group inline-style-51">
                    <label>Email Address</label>
                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                        placeholder="user@example.com" required>
                    @error('email') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group inline-style-52">
                    <label>Detailed Address (No, Street, Ward)</label>
                    <input type="text" name="address_line" id="address_line" value="{{ old('address_line') }}"
                        placeholder="No.12, Zay Street..." required>
                    @error('address_line') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Township</label>
                        <input type="text" name="township" id="township" value="{{ old('township') }}"
                            placeholder="e.g. Kamayut" required>
                        @error('township') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>City / Region</label>
                        <select name="region" id="regionSelect" class="inline-style-53" required onchange="handleRegionChange()">
                            <option value="">Select a region...</option>
                            @foreach($shippingRates as $rate)
                                <option value="{{ $rate->region_name }}" {{ old('region') == $rate->region_name ? 'selected' : '' }}>
                                    {{ $rate->region_name }}
                                </option>
                            @endforeach
                            <option value="Other" {{ old('region') == 'Other' ? 'selected' : '' }}>Other Regions</option>
                        </select>
                        @error('region') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                </div>

                <h2 class="section-title inline-style-54">3. Payment Method</h2>

                <input type="hidden" name="payment_method" id="payment_method" value="kpay">
                <div class="payment-methods">
                    <div class="payment-method active" id="btnKPay" onclick="selectPayment('kpay')">
                        <i class="fa-solid fa-mobile-screen"></i>
                        <div class="inline-style-55">KPay</div>
                    </div>
                    <div class="payment-method" id="btnWavePay" onclick="selectPayment('wave')">
                        <i class="fa-solid fa-money-bill-transfer"></i>
                        <div class="inline-style-56">Wave Pay</div>
                    </div>
                    <div class="payment-method" id="btnCOD" onclick="selectPayment('cod')">
                        <i class="fa-solid fa-truck"></i>
                        <div class="inline-style-57">Cash on Delivery</div>
                        <div class="inline-style-58" id="codWarning">Only available for Yangon & Mandalay</div>
                    </div>
                </div>

                <div class="form-group inline-style-59" id="paymentProofSection">
                    <p class="inline-style-60">Please transfer the total amount to
                        <strong>09-123456789 (Campus Supply)</strong> and upload the transaction screenshot below.
                    </p>
                    <label>Upload Payment Screenshot</label>
                    <input type="file" name="payment_slip" id="payment_slip" accept=".jpg,.jpeg,.png"
                        class="inline-style-61">
                    @error('payment_slip') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

            </div>
            <!-- End Left Column -->

            <!-- Right Column -->
            <div class="right-col">
                <div class="summary-box">
                    <h2 class="section-title inline-style-62">Order Summary</h2>
                    <div class="summary-row">
                        <span>Subtotal ({{ count($cartItems) }} items)</span>
                        <span class="inline-style-63">{{ number_format($subtotal) }} Ks</span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span class="inline-style-64" id="shippingDisplay">{{ number_format($shipping) }} Ks</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span id="totalDisplay">{{ number_format($total) }} Ks</span>
                    </div>
                    <button type="submit" class="btn-checkout">
                        <i class="fa-solid fa-lock inline-style-65"></i> Place Order
                    </button>
                </div>
            </div>
            <!-- End Right Column -->

        </div>
    </form>

@endsection

@push('scripts')
    <script>
        function removeItem(variantId) {
            document.getElementById('remove-form-' + variantId).submit();
        }

        async function updateCheckoutQuantity(key, newQty) {
            if (newQty < 1) return;
            
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('key', key);
            formData.append('quantity', newQty);
            
            try {
                const response = await fetch('{{ route("cart.update-ajax") }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 'Accept': 'application/json' }
                });
                const data = await response.json();
                if (!response.ok || (data && data.success === false)) {
                    if (data.errors && data.errors.quantity) {
                        showAlertModal(data.errors.quantity[0], 'Error');
                    } else {
                        showAlertModal(data.message || 'Cannot update quantity.', 'Error');
                    }
                } else {
                    window.location.reload();
                }
            } catch (e) {
                console.error("Update error:", e);
                window.location.reload();
            }
        }

        const shippingRates = @json($shippingRates);
        const subtotal = {{ $subtotal }};
        const totalItems = {{ collect($cartItems)->sum('quantity') }};

        function handleRegionChange() {
            const region = document.getElementById('regionSelect').value.trim().toLowerCase();
            const btnCOD = document.getElementById('btnCOD');
            const codWarning = document.getElementById('codWarning');

            if (region === 'yangon' || region === 'mandalay' || region === '') {
                btnCOD.style.opacity = '1';
                btnCOD.style.pointerEvents = 'auto';
                codWarning.style.display = 'none';
            } else {
                btnCOD.style.opacity = '0.5';
                btnCOD.style.pointerEvents = 'none';
                if (document.getElementById('payment_method').value === 'cod') {
                    selectPayment('kpay');
                }
                codWarning.style.display = 'block';
            }

            // Calculate Shipping Dynamically
            let shipping = 3000; // default
            if(region !== '') {
                let foundRate = shippingRates.find(r => r.region_name.toLowerCase() === region);
                if (foundRate) {
                    shipping = foundRate.base_fee + (foundRate.extra_fee_per_item * Math.max(0, totalItems - 1));
                }
            } else {
                shipping = 0; // Don't show shipping until region selected if you want, or show default. Let's show 0.
            }

            let total = subtotal + shipping;
            document.getElementById('shippingDisplay').innerText = new Intl.NumberFormat('en-US').format(shipping) + ' Ks';
            document.getElementById('totalDisplay').innerText = new Intl.NumberFormat('en-US').format(total) + ' Ks';
        }

        function selectPayment(method) {
            document.getElementById('payment_method').value = method;
            document.querySelectorAll('.payment-method').forEach(b => b.classList.remove('active'));

            if (method === 'kpay') document.getElementById('btnKPay').classList.add('active');
            if (method === 'wave') document.getElementById('btnWavePay').classList.add('active');
            if (method === 'cod') document.getElementById('btnCOD').classList.add('active');

            const proofSection = document.getElementById('paymentProofSection');
            const slipInput = document.getElementById('payment_slip');

            if (method === 'cod') {
                proofSection.style.display = 'none';
                slipInput.required = false;
            } else {
                proofSection.style.display = 'block';
                slipInput.required = true;
            }
        }

        function fillAddressForm() {
            const select = document.getElementById('savedAddressSelect');
            const option = select.options[select.selectedIndex];

            if (option.value) {
                document.getElementById('address_line').value = option.getAttribute('data-line');
                const cityParts = option.getAttribute('data-city').split(',');
                if (cityParts.length > 1) {
                    document.getElementById('township').value = cityParts[0].trim();
                    document.getElementById('regionSelect').value = cityParts[1].trim();
                } else {
                    document.getElementById('township').value = cityParts[0].trim();
                    document.getElementById('regionSelect').value = cityParts[0].trim();
                }
                document.getElementById('phone').value = option.getAttribute('data-phone');
                handleRegionChange();
            } else {
                document.getElementById('address_line').value = '';
                document.getElementById('township').value = '';
                document.getElementById('regionSelect').value = '';
                document.getElementById('phone').value = '';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            selectPayment('kpay');
            
            const savedAddressSelect = document.getElementById('savedAddressSelect');
            if(savedAddressSelect && savedAddressSelect.options.length > 1 && !document.getElementById('address_line').value) {
                savedAddressSelect.selectedIndex = 1;
                fillAddressForm();
            } else {
                handleRegionChange();
            }
        });
    </script>
@endpush