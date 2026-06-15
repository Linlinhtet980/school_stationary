<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Campus Supply</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- External CSS -->
    <link rel="stylesheet" href="css/customer.css">
    <link rel="stylesheet" href="css/checkout.css"> <!-- Checkout Specific CSS -->
</head>
<body>

    <!-- NAVBAR (Copy from master.html) -->
    <nav class="navbar"> ... </nav>

    <!-- MAIN CONTENT -->
    <main class="main-wrapper">
        
        <div class="checkout-container">
            
            <!-- LEFT: Checkout Form -->
            <div class="checkout-form">
                <form id="checkoutForm">
                    
                    <h2 class="checkout-section-title">1. Shipping Details</h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">First Name *</label>
                            <input type="text" name="first_name" class="form-input" required placeholder="Aung">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Last Name *</label>
                            <input type="text" name="last_name" class="form-input" required placeholder="Kyaw">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Phone Number *</label>
                            <input type="tel" name="phone" class="form-input" required placeholder="09xxxxxxxxx">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-input" placeholder="optional@email.com">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Full Delivery Address *</label>
                        <textarea name="address" class="form-input" rows="3" required placeholder="No.123, Bogyoke Road, Yangon..."></textarea>
                    </div>

                    <h2 class="checkout-section-title" style="margin-top: 3rem;">2. Payment Method</h2>
                    
                    <div class="payment-methods">
                        <!-- COD Option -->
                        <label class="payment-card active">
                            <input type="radio" name="payment_method" value="cod" class="payment-radio" checked>
                            <div class="payment-info">
                                Cash on Delivery (COD)
                                <div class="payment-desc">Pay with cash upon delivery.</div>
                            </div>
                        </label>

                        <!-- KPay Option -->
                        <label class="payment-card">
                            <input type="radio" name="payment_method" value="kpay" class="payment-radio">
                            <div class="payment-info">
                                KBZPay (KPay)
                                <div class="payment-desc">Scan the QR code to pay digitally.</div>
                            </div>
                        </label>
                        
                        <!-- KPay Hidden Details -->
                        <div class="kpay-details" id="kpayDetails">
                            <p style="font-weight: 800; color: var(--secondary);">Scan QR to Pay 33,500 Ks</p>
                            <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg" class="kpay-qr" alt="KPay QR">
                            <p style="font-size: 0.85rem; color: #718096;">Account Name: Campus Supply (09123456789)</p>
                            
                            <div class="form-group" style="margin-top: 1rem; text-align: left;">
                                <label class="form-label">Transaction ID (Last 6 Digits) *</label>
                                <input type="text" class="form-input" placeholder="e.g. 123456">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-place-order">PLACE ORDER</button>
                </form>
            </div>

            <!-- RIGHT: Order Summary -->
            <div class="order-summary">
                <h2 class="checkout-section-title">Order Summary</h2>
                
                <div class="summary-item">
                    <img src="https://images.unsplash.com/photo-1531346878377-a5f20ce31158?w=150" class="summary-item-img" alt="Item">
                    <div class="summary-item-info">
                        <div class="summary-item-title">Eco-Friendly A5 Notebook</div>
                        <div class="summary-item-qty">Qty: 2</div>
                    </div>
                    <div class="summary-item-price">17,000 Ks</div>
                </div>

                <div class="summary-item">
                    <img src="https://images.unsplash.com/photo-1585336261022-680e295ce3fe?w=150" class="summary-item-img" alt="Item">
                    <div class="summary-item-info">
                        <div class="summary-item-title">Premium Gel Pens Set</div>
                        <div class="summary-item-qty">Qty: 1</div>
                    </div>
                    <div class="summary-item-price">12,000 Ks</div>
                </div>

                <div class="summary-totals">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>29,000 Ks</span>
                    </div>
                    <div class="summary-row">
                        <span>Delivery Fee</span>
                        <span>4,500 Ks</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span>33,500 Ks</span>
                    </div>
                </div>
            </div>

        </div>

    </main>

    <!-- FOOTER (Copy from master.html) -->
    <footer class="footer"> ... </footer>

    <!-- External JS -->
    <script src="js/customer.js"></script>
    <script src="js/checkout.js"></script> <!-- Checkout Specific JS -->
</body>
</html>