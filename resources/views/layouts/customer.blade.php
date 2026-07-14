<!DOCTYPE html>
<html lang="my">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Campus Supply')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/customer/views/global.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/customer/views/customer.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/customer/views/layout.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/customer/views/components.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/customer/views/prototype.css') }}?v={{ time() }}">
    @stack('styles')
</head>
<body class="{{ auth()->check() ? 'authenticated' : '' }}">

    <!-- ═══ Page Loading Overlay ═══ -->
    <div id="pageLoader">
        <div class="loader-content">
            <img src="{{ asset('logo.png') }}" alt="Loading..." class="loader-logo">
            <div class="loader-ring"></div>
            <span class="loader-label">Loading…</span>
        </div>
    </div>

    <nav class="navbar">
        <button type="button" id="mobileMenuBtn" class="mobile-menu-btn">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="logo-container">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="logo-img">
            <a href="{{ route('home') }}" class="inline-style-122">
                <div class="logo-text">CAMPUS<span>SUPPLY</span></div>
            </a>
        </div>
        <div class="nav-right-bg">
            <div class="nav-links">
                <a href="{{ route('shop.index') }}" class="{{ request()->routeIs('shop.index') ? 'active' : '' }}">PRODUCTS</a>
                <a href="{{ route('shop.new-arrivals') }}" class="{{ request()->routeIs('shop.new-arrivals') ? 'active' : '' }}">NEW ARRIVALS</a>
                <a href="{{ route('shop.bestsellers') }}" class="{{ request()->routeIs('shop.bestsellers') ? 'active' : '' }}">BESTSELLERS</a>
                <a href="{{ route('shop.b2s-deals') }}" class="{{ request()->routeIs('shop.b2s-deals') ? 'active' : '' }}">B2S DEALS</a>
                <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">ABOUT US</a>
            </div>
            <div class="nav-actions">
                <div class="search-bar">
                    <form action="{{ route('shop.index') }}" method="GET" class="inline-style-123">
                        <input type="text" name="search" id="headerSearchInput" value="{{ request('search') }}" placeholder="Search products..." autocomplete="off">
                        <button type="submit" class="inline-style-124"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </form>
                    <div id="searchLiveResults" class="search-live-results"></div>
                </div>
                <div class="icons">
                    @auth
                        <a href="{{ route('profile.index') }}" class="inline-style-125"><i class="fa-regular fa-user"></i></a>
                        <a href="{{ route('profile.wishlist') }}" class="inline-style-126"><i class="fa-regular fa-heart"></i></a>
                        
                        <!-- Notification Dropdown -->
                        <div class="notification-dropdown-container" style="position: relative; display: inline-block;">
                            <a href="#" id="notiIconBtn" class="inline-style-126" onclick="document.getElementById('notiDropdown').classList.toggle('show'); return false;">
                                <div class="cart-wrapper">
                                    <i class="fa-regular fa-bell"></i>
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                        <span class="cart-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                                    @endif
                                </div>
                            </a>
                            <div id="notiDropdown" class="noti-dropdown">
                                <div class="noti-header">
                                    <h4>Notifications</h4>
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                        <form action="{{ route('profile.notifications.markAllRead') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="mark-read-btn">Mark all as read</button>
                                        </form>
                                    @endif
                                </div>
                                <div class="noti-body">
                                    @forelse(auth()->user()->notifications()->take(5)->get() as $notification)
                                        <a href="{{ route('profile.notifications.markRead', $notification->id) }}" class="noti-item {{ $notification->read_at ? '' : 'unread' }}">
                                            <div class="noti-icon">
                                                @if(isset($notification->data['type']) && $notification->data['type'] == 'order_status')
                                                    <i class="fa-solid fa-box"></i>
                                                @else
                                                    <i class="fa-solid fa-heart"></i>
                                                @endif
                                            </div>
                                            <div class="noti-content">
                                                <p>{{ $notification->data['message'] ?? 'New notification' }}</p>
                                                <small>{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="noti-empty">No notifications yet.</div>
                                    @endforelse
                                </div>
                                <div class="noti-footer">
                                    <a href="{{ route('profile.index') }}">View all in Profile</a>
                                </div>
                            </div>
                        </div>

                        <a href="#" id="cartIconBtn" class="inline-style-129">
                            <div class="cart-wrapper">
                                <i class="fa-solid fa-cart-shopping"></i>
                                <span class="cart-badge">{{ session('cart') ? count(session('cart')) : 0 }}</span>
                            </div>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-login-header">
                            Login / Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Slide-out Sidebar -->
    <div id="mobileOverlay" class="mobile-overlay"></div>
    <div id="mobileSidebar" class="mobile-sidebar">
        <div class="mobile-sidebar-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <img src="{{ asset('logo.png') }}" alt="Logo" style="width: 35px; height: 35px; object-fit: contain; border-radius: 6px;">
                <div class="logo-text" style="font-size: 1.2rem; font-weight: 800;">CAMPUS<span style="color: var(--primary);">SUPPLY</span></div>
            </div>
            <button type="button" id="closeMobileMenuBtn" class="close-mobile-btn"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="mobile-search">
            <form action="{{ route('shop.index') }}" method="GET">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products...">
                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </div>
        <div class="mobile-nav-links">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}"><i class="fa-solid fa-house" style="width: 25px;"></i> HOME</a>
            <a href="{{ route('shop.index') }}" class="{{ request()->routeIs('shop.index') ? 'active' : '' }}"><i class="fa-solid fa-box-open" style="width: 25px;"></i> PRODUCTS</a>
            <a href="{{ route('shop.new-arrivals') }}" class="{{ request()->routeIs('shop.new-arrivals') ? 'active' : '' }}"><i class="fa-solid fa-wand-magic-sparkles" style="width: 25px;"></i> NEW ARRIVALS</a>
            <a href="{{ route('shop.bestsellers') }}" class="{{ request()->routeIs('shop.bestsellers') ? 'active' : '' }}"><i class="fa-solid fa-fire" style="width: 25px;"></i> BESTSELLERS</a>
            <a href="{{ route('shop.b2s-deals') }}" class="{{ request()->routeIs('shop.b2s-deals') ? 'active' : '' }}"><i class="fa-solid fa-tags" style="width: 25px;"></i> B2S DEALS</a>
            <hr style="margin: 1rem 0; border: none; border-top: 1px solid #eee;">
            @auth
                <a href="{{ route('profile.index') }}" class="{{ request()->routeIs('profile.*') && !request()->routeIs('profile.wishlist') ? 'active' : '' }}"><i class="fa-regular fa-user" style="width: 25px;"></i> PROFILE</a>
                <a href="{{ route('profile.wishlist') }}" class="{{ request()->routeIs('profile.wishlist') ? 'active' : '' }}"><i class="fa-regular fa-heart" style="width: 25px;"></i> WISHLIST</a>
            @else
                <a href="{{ route('login') }}"><i class="fa-regular fa-user" style="width: 25px;"></i> LOGIN / REGISTER</a>
            @endauth
        </div>
    </div>

    @yield('content')

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
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('shop.index') }}">Shop All</a></li>
                    <li><a href="{{ route('shop.new-arrivals') }}">New Arrivals</a></li>
                    <li><a href="{{ route('shop.bestsellers') }}">Bestsellers</a></li>
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
                <p><i class="fa-solid fa-location-dot inline-style-130" ></i> Room-604,5th Floor,Building-C,Golden Link Condo</p>
                <p><i class="fa-solid fa-phone inline-style-131" ></i> +95 9409102277</p>
                <p><i class="fa-solid fa-envelope inline-style-132" ></i> info@o-technique-myanmar.com</p>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; 2026 Campus Supply. All rights reserved.
        </div>
    </footer>

    <!-- Slide-out Cart Drawer -->
    <div class="cart-overlay" id="cartDrawerOverlay"></div>
    <div class="cart-drawer" id="cartDrawer">
        <div class="cart-header">
            <h3>Your Cart</h3>
            <button class="close-cart" id="closeCartBtn"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="cart-items" id="cartDrawerBody">
            <div style="text-align:center; padding:20px;">Loading cart...</div>
        </div>
        <div id="cartDrawerEmpty" style="display:none; text-align:center; padding:20px;">
            <p>Your cart is empty.</p>
        </div>
        <div class="cart-footer" id="cartDrawerFooter">
            <div class="cart-total">
                <span>Total:</span>
                <span id="cartDrawerTotal">0 Ks</span>
            </div>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('cart.index') }}" class="btn-checkout-drawer" style="background: #f1f1f1; color: var(--secondary);">View Cart</a>
                <a href="{{ route('checkout.index') }}" class="btn-checkout-drawer">Checkout</a>
            </div>
        </div>
    </div>

    
    <script>
        // Smart Sticky Navbar (Hide on scroll down, Show on scroll up)
        let lastScrollTop = 0;
        const navbar = document.querySelector('.navbar');
        
        window.addEventListener('scroll', function() {
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const floatingBtn = document.getElementById('floatingCartBtn');
            
            // Glassmorphism effect when scrolled
            if (scrollTop > 50) {
                if (navbar) navbar.classList.add('scrolled');
            } else {
                if (navbar) navbar.classList.remove('scrolled');
            }

            // Floating Cart Show/Hide logic
            if (scrollTop > 200) {
                if(floatingBtn) floatingBtn.classList.add('show');
            } else {
                if(floatingBtn) floatingBtn.classList.remove('show');
            }

            // Hide/Show logic for navbar
            if (scrollTop > lastScrollTop && scrollTop > 100) {
                // Downscroll - hide
                if (navbar) navbar.classList.add('nav-up');
            } else {
                // Upscroll - show
                if (navbar) navbar.classList.remove('nav-up');
            }
            lastScrollTop = scrollTop;
        });
    </script>
    @stack('scripts')


    <script src="{{ asset('js/customer/layout.js') }}"></script>
    <!-- Floating Cart Button -->
    <a href="#" id="floatingCartBtn" class="floating-cart" onclick="document.getElementById('cartIconBtn').click(); return false;">
        <i class="fa-solid fa-cart-shopping"></i>
        <span class="cart-badge">{{ session('cart') ? count(session('cart')) : 0 }}</span>
    </a>

    <script src="{{ asset('js/page-loader.js') }}?v={{ time() }}"></script>
    @include('partials.confirm_modal')
    @include('partials.alert_modal')
</body>
</html>
