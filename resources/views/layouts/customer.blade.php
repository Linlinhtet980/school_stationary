<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Campus Supply - Store')</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800;900&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- External Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/layouts/customer.css') }}">
    
    @stack('styles')
</head>
<body>

    <!-- 1. NAVBAR (Global) -->
    <nav class="navbar">
        <a href="{{ route('default') }}" class="logo-container">
            <div class="logo">
                <img src="{{ asset('logo.png') }}" alt="CampusSupply Logo" class="logo-img">
            </div>
            <div class="logo-text">CAMPUS<span>SUPPLY</span></div>
        </a>
        <div class="nav-right-bg">
            <div class="nav-links">
                @auth
                    <a href="{{ route('home') }}" class="active">HOME</a>
                @else
                    <a href="{{ route('shop.index') }}" class="active">HOME</a>
                @endauth
                <a href="{{ route('shop.index') }}">PRODUCTS</a>
                <a href="{{ route('shop.new-arrivals') }}">NEW ARRIVALS</a>
                <a href="{{ route('shop.bestsellers') }}">BESTSELLERS</a>
                <a href="{{ route('shop.b2s-deals') }}">Bundles DEALS</a>
            </div>
            <div class="nav-actions">
                <div class="search-bar">
                    <form action="{{ route('shop.search') }}" method="GET">
                        <input type="text" name="q" placeholder="Search products...">
                        <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </form>
                </div>
                <div class="icons">
                    @auth
                        <!-- Profile Dropdown -->
                        <div class="profile-dropdown">
                            <button class="icon-link" title="My Profile" onclick="toggleProfileDropdown()">
                                <i class="fa-regular fa-user"></i>
                            </button>
                            <div class="dropdown-menu" id="profileDropdown">
                                <a href="{{ route('profile.index') }}" class="dropdown-item">
                                    <i class="fa-regular fa-user"></i> My Profile
                                </a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                        <a href="{{ route('profile.wishlist') }}" class="icon-link" title="Wishlist">
                            <i class="fa-regular fa-heart"></i>
                        </a>
                        <!-- Cart (guests can view but need to login to add) -->
                        <a href="{{ route('cart.index') }}" class="icon-link" title="Cart">
                            <div class="cart-wrapper">
                                <i class="fa-solid fa-cart-shopping"></i>
                                <span class="cart-badge">{{ session('cart') ? count(session('cart')) : 0 }}</span>
                            </div>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="icon-link" title="Login">
                            <i class="fa-regular fa-user"></i>
                        </a>
                        <a href="{{ route('login') }}" class="icon-link" title="Wishlist">
                            <i class="fa-regular fa-heart"></i>
                        </a>
                        <!-- Cart (guests can view but need to login to add) -->
                        <a href="{{ route('cart.index') }}" class="icon-link" title="Cart">
                            <div class="cart-wrapper">
                                <i class="fa-solid fa-cart-shopping"></i>
                                <span class="cart-badge">{{ session('cart') ? count(session('cart')) : 0 }}</span>
                            </div>
                        </a>
                    @endauth
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
                    <li><a href="{{ route('shop.index') }}">Shop All</a></li>
                    <li><a href="{{ route('shop.new-arrivals') }}">New Arrivals</a></li>
                    <li><a href="{{ route('shop.bestsellers') }}">Bestsellers</a></li>
                    <li><a href="{{ route('shop.b2s-deals') }}">B2S Deals</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>CUSTOMER SERVICE</h3>
                <ul class="footer-links">
                    @auth
                        <li><a href="{{ route('profile.orders') }}">Track Order</a></li>
                        <li><a href="{{ route('profile.index') }}">My Profile</a></li>
                    @else
                        <li><a href="{{ route('login') }}">Track Order</a></li>
                        <li><a href="{{ route('login') }}">My Profile</a></li>
                    @endauth
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Shipping & Returns</a></li>
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

    <!-- 4. External Custom JS -->
    <script src="{{ asset('js/layouts/customer.js') }}"></script>
    <script src="{{ asset('js/customer/layout.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
