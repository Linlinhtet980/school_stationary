<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Supply - Store</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800;900&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- External Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/layouts/customer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer/home.css') }}">
</head>
<body>

    <!-- 1. NAVBAR (Global) -->
    <nav class="navbar">
        <a href="index.html" class="logo-container">
            <div class="logo">
                <img src="{{ asset('logo.png') }}" alt="CampusSupply Logo" class="logo-img">
            </div>
            <div class="logo-text">CAMPUS<br>SUPPLY</div>
        </a>
        <div class="nav-right-bg">
            <div class="nav-links">
                <a href="home.blade.php" class="active">HOME</a>
                <a href="shop.html">PRODUCTS</a>
                <a href="new_arrivals.html">NEW ARRIVALS</a>
                <a href="bestsellers.html">BESTSELLERS</a>
                <a href="bundles_plans.html">Bundles Plan</a>
            </div>
            <div class="nav-actions">
                <div class="search-bar">
                    <input type="text" placeholder="Search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <div class="icons">
                    <a href="login.html" class="icon-link"><i class="fa-regular fa-user"></i></a>
                    <a href="wishlist.html" class="icon-link"><i class="fa-regular fa-heart"></i></a>
                    <!-- ID added for JS trigger -->
                    <a href="#" id="cartIconBtn" class="icon-link">
                        <div class="cart-wrapper">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <span class="cart-badge">3</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- 2. MAIN CONTENT WRAPPER (Dynamic Content) -->
    <main class="main-wrapper">
        
        @yield('content')

    </main>

    <!-- 3. FOOTER (Global) -->
    <footer class="footer">
        <div class="footer-grid">
            <div class="footer-col">
                <h3>CAMPUS SUPPLY</h3>
                <p>Your one-stop premium stationery store. We provide high-quality school and office supplies to fuel your creativity and productivity.</p>
                <div class="social-icons">
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-twitter"></i></a>
                </div>
            </div>
            <div class="footer-col">
                <h3>QUICK LINKS</h3>
                <ul class="footer-links">
                    <li><a href="index.html">Home</a></li>
                    <li><a href="shop.html">Shop All</a></li>
                    <li><a href="new_arrivals.html">New Arrivals</a></li>
                    <li><a href="bestsellers.html">Bestsellers</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>CUSTOMER SERVICE</h3>
                <ul class="footer-links">
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Shipping & Returns</a></li>
                    <li><a href="#">Track Order</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>CONTACT INFO</h3>
                <p><i class="fa-solid fa-location-dot contact-icon"></i>Room-604,5th Floor,Building-C,Golden Link Condo,Link Ln,Yangon,Myanmar.</p>
                <p><i class="fa-solid fa-phone contact-icon"></i> +95 9409102277 , +95 9409101177</p>
                <p><i class="fa-solid fa-envelope contact-icon"></i>info@o-technique-myanmar.com</p>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; 2026 Campus Supply. All rights reserved.
        </div>
    </footer>

    <!-- 4. SLIDE-OUT CART DRAWER (Global) -->
    <div class="cart-overlay" id="cartOverlay"></div>
    <div class="cart-drawer" id="cartDrawer">
        <div class="cart-header">
            <h3>Your Cart</h3>
            <!-- ID added for JS trigger -->
            <button class="close-cart" id="closeCartBtn"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="cart-items">
            <p style="text-align:center; color:#888; margin-top:2rem;">Cart is empty.</p>
            <!-- Cart items will be injected here dynamically -->
        </div>
        <div class="cart-footer">
            <div class="cart-total">
                <span>Total</span>
                <span>0 Ks</span>
            </div>
            <a href="cart-checkout.html" class="btn-checkout-drawer">Checkout</a>
        </div>
    </div>

    <!-- External Custom JS -->
    <script src="/js/customer.js"></script>
</body>
</html>