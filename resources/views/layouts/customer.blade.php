<!DOCTYPE html>
<html lang="my">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Campus Supply')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('styles')
    <link rel="stylesheet" href="{{ asset('css/customer/views/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer/views/customer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer/views/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer/views/prototype.css') }}">
</head>
<body>

    <nav class="navbar">
        <div class="logo-container">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="logo-img">
            <a href="{{ route('home') }}" class="inline-style-122">
                <div class="logo-text">CAMPUS<span>SUPPLY</span></div>
            </a>
        </div>
        <div class="nav-right-bg">
            <div class="nav-links">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">HOME</a>
                <a href="{{ route('shop.index') }}" class="{{ request()->routeIs('shop.index') ? 'active' : '' }}">PRODUCTS</a>
                <a href="{{ route('shop.new-arrivals') }}" class="{{ request()->routeIs('shop.new-arrivals') ? 'active' : '' }}">NEW ARRIVALS</a>
                <a href="{{ route('shop.bestsellers') }}" class="{{ request()->routeIs('shop.bestsellers') ? 'active' : '' }}">BESTSELLERS</a>
                <a href="{{ route('shop.b2s-deals') }}" class="{{ request()->routeIs('shop.b2s-deals') ? 'active' : '' }}">B2S DEALS</a>
            </div>
            <div class="nav-actions">
                <div class="search-bar">
                    <form action="{{ route('shop.search') }}" method="GET" class="inline-style-123">
                        <input type="text" name="q" placeholder="Search">
                        <button type="submit" class="inline-style-124"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </form>
                </div>
                <div class="icons">
                    @auth
                        <a href="{{ route('profile.index') }}" class="inline-style-125"><i class="fa-regular fa-user"></i></a>
                        <a href="{{ route('profile.wishlist') }}" class="inline-style-126"><i class="fa-regular fa-heart"></i></a>
                    @else
                        <a href="{{ route('login') }}" class="inline-style-127"><i class="fa-regular fa-user"></i></a>
                        <a href="{{ route('login') }}" class="inline-style-128"><i class="fa-regular fa-heart"></i></a>
                    @endauth
                    
                    <a href="#" id="cartIcon" class="inline-style-129">
                        <div class="cart-wrapper">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <span class="cart-badge">{{ session('cart') ? count(session('cart')) : 0 }}</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </nav>

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
    <div class="cart-overlay" id="cartOverlay"></div>
    <div class="cart-drawer" id="cartDrawer">
        <div class="cart-header">
            <h3>Your Cart</h3>
            <button class="close-cart" id="closeCartBtn"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="cart-items" id="cartItemsContainer">
            <div id="cartItemsList">
                <div style="text-align:center; padding:20px;">Loading cart...</div>
            </div>
        </div>
        <div class="cart-footer">
            <div class="cart-total">
                <span>Total:</span>
                <span id="cartTotalDisplay">0 Ks</span>
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


<script>
// Cart Drawer Logic
        const cartIcon = document.getElementById('cartIcon');
        const cartDrawer = document.getElementById('cartDrawer');
        const cartOverlay = document.getElementById('cartOverlay');
        const closeCartBtn = document.getElementById('closeCartBtn');

        function loadCartDrawer() {
            fetch('{{ route('cart.get-items') }}', {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                const container = document.getElementById('cartItemsList');
                if (data.items.length === 0) {
                    container.innerHTML = '<p style="padding:20px; text-align:center;">Your cart is empty.</p>';
                    document.getElementById('cartTotalDisplay').innerText = '0 Ks';
                    return;
                }
                
                let html = '';
                data.items.forEach((item, index) => {
                    let priceFormat = new Intl.NumberFormat().format(item.price);
                    html += `
                    <div class="drawer-item" style="position:relative; padding-bottom: 15px; border-bottom: 1px solid #eee; align-items: flex-start;">
                        <img src="${item.image}" alt="${item.name}" style="margin-top: 5px;">
                        <div class="drawer-item-info">
                            <h4 style="padding-right: 25px; line-height: 1.4;">${item.name}</h4>
                            <div class="drawer-item-price" style="margin-bottom: 10px; font-size: 1.05rem;">${priceFormat} Ks</div>
                            <div class="drawer-qty" style="background: #f8f9fa; border: 1px solid #ddd; border-radius: 6px; padding: 2px;">
                                <button onclick="updateDrawerQuantity('${item.key}', ${parseInt(item.quantity) - 1})" style="width: 28px; height: 28px; border-radius: 4px; background: white; border: 1px solid #eee; cursor: pointer;">-</button>
                                <span style="font-weight: 800; font-size: 0.9rem; width: 35px; text-align: center; display: inline-block;">${item.quantity}</span>
                                <button onclick="updateDrawerQuantity('${item.key}', ${parseInt(item.quantity) + 1})" style="width: 28px; height: 28px; border-radius: 4px; background: white; border: 1px solid #eee; cursor: pointer;">+</button>
                            </div>
                        </div>
                        <button onclick="removeDrawerItem('${item.key}')" style="position:absolute; top:0; right:0; color:#E53E3E; background:none; border:none; cursor:pointer; font-size: 1.2rem; padding: 5px; transition: 0.2s;" onmouseover="this.style.color='#c53030'" onmouseout="this.style.color='#E53E3E'"><i class="fa-solid fa-trash"></i></button>
                    </div>`;
                });
                container.innerHTML = html;
                document.getElementById('cartTotalDisplay').innerText = new Intl.NumberFormat().format(data.total) + ' Ks';
                
                const badges = document.querySelectorAll('.cart-badge');
                badges.forEach(badge => badge.innerText = data.cart_count);
            }).catch(e => {
                document.getElementById('cartItemsList').innerHTML = '<p style="padding:20px; text-align:center; color:red;">Error loading cart.</p>';
            });
        }

        const openCart = (e) => {
            if(e) e.preventDefault();
            loadCartDrawer();
            cartDrawer.classList.add('open');
            cartOverlay.classList.add('open');
        };

        const closeCart = () => {
            cartDrawer.classList.remove('open');
            cartOverlay.classList.remove('open');
        };

        if (cartIcon) cartIcon.addEventListener('click', openCart);
        closeCartBtn.addEventListener('click', closeCart);
        cartOverlay.addEventListener('click', closeCart);

        function updateCartQuantity(id, change) {
            fetch(`/cart/update/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ change: change })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                }
            });
        }
        
        async function updateDrawerQuantity(key, newQty) {
            if(newQty < 1) return;
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
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
                    // Check if there are validation errors
                    if (data.errors && data.errors.quantity) {
                        alert(data.errors.quantity[0]);
                    } else {
                        alert(data.message || 'Cannot update quantity.');
                    }
                }
            } catch (e) {
                console.error("Update error:", e);
            } finally {
                loadCartDrawer();
            }
        }

        async function removeDrawerItem(key) {
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            formData.append('key', key);
            await fetch('{{ route("cart.remove-ajax") }}', { method: 'POST', body: formData });
            loadCartDrawer();
        }
        
        // Expose openCart globally so other files can use it
        window.openCart = openCart;

        // Auto-open cart drawer if item was just added (Legacy fallback)
        @if(session('cart_open'))
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => openCart(), 200);
        });
        @endif

        // Intercept Add to Cart forms to use AJAX
        document.addEventListener('submit', async function(e) {
            const form = e.target;

            // Bundle forms - just disable button to prevent double submit, let normal submit happen
            if (form.action && form.action.includes('/cart/add-bundle')) {
                const btn = form.querySelector('button[type="submit"]');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Adding...';
                }
                return; // Let normal form submit happen
            }

            if (form.action && form.action.includes('/cart/add') && !form.action.includes('/cart/add-bundle')) {
                e.preventDefault();
                
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn ? submitBtn.innerHTML : '';
                if (submitBtn) submitBtn.innerHTML = 'Adding...';

                try {
                    const formData = new FormData(form);
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    const data = await response.json();
                    if (data.success) {
                        loadCartDrawer();
                        openCart();
                    } else {
                        alert(data.message || 'Error adding item to cart.');
                    }
                } catch (error) {
                    console.error('Add to cart error:', error);
                    alert('Network error. Please try again.');
                } finally {
                    if (submitBtn) submitBtn.innerHTML = originalText;
                }
            }
        });
</script>
    <!-- Floating Cart Button -->
    <a href="#" id="floatingCartBtn" class="floating-cart" onclick="openCart(event)">
        <i class="fa-solid fa-cart-shopping"></i>
        <span class="cart-badge">{{ session('cart') ? count(session('cart')) : 0 }}</span>
    </a>

</body>
</html>
