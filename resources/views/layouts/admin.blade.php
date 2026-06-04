<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - School Stationary</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Link to external CSS file instead of inline styles -->
    <link rel="stylesheet" href="{{ asset('css/layouts/admin.css') }}">
    
    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">
            <img src="../logo.png" alt="Logo" class="logo-img">
            <div class="logo-text">CAMPUS <span>SUPPLY</span></div>
        </div>
        
        <div class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-house"></i> Dashboard</a>
            
            <a href="#"><i class="fa-solid fa-cart-shopping"></i> Orders</a>
            
            <div class="has-dropdown">
                <div class="dropdown-toggle" onclick="toggleDropdown('catalog-menu')">
                    <div class="menu-item-content"><i class="fa-solid fa-box-open"></i> Products</div>
                    <i class="fa-solid fa-chevron-down dropdown-icon"></i>
                </div>
                <div class="dropdown-menu {{ request()->routeIs('admin.categories.*') ? 'show' : '' }}" id="catalog-menu">
                    <a href="#">All Products</a>
                    <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">Categories</a>
                    <a href="#">Brands</a>
                    <a href="{{ route('admin.types.index') }}" class="{{ request()->routeIs('admin.types.*') ? 'active' : '' }}">Types</a>
                </div>
            </div>
            
            <a href="#"><i class="fa-solid fa-users"></i> Customers</a>
        </div>
    </div>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <div class="topbar">
            <h1 id="page-title">@yield('header_title', 'Welcome Back, Admin!')</h1>
            
            <div class="topbar-search-container">
                <div class="search-box">
                    <i class="fa-solid fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Search Bar">
                </div>
            </div>

            <div class="topbar-actions">
                <div class="notification-bell">
                    <i class="fa-regular fa-bell bell-icon"></i>
                    <span class="notification-badge">2</span>
                </div>
                <div class="profile-menu">
                    <div class="avatar">SA</div>
                    <div class="profile-details">
                        <div class="profile-name">{{ Auth::user()->name ?? 'Super Admin' }}</div>
                        <div class="profile-role">System Admin</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content Area -->
        <div class="main-content">
            @yield('content')
        </div>
    </div>

    <script src="{{ asset('js/layouts/admin.js') }}"></script>
    @stack('scripts')
</body>
</html>
