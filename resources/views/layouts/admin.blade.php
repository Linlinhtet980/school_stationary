<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - School Stationary</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Link to external CSS file instead of inline styles -->
    <link rel="stylesheet" href="{{ asset('css/layouts/admin.css?v=' . time()) }}">

    @stack('styles')

    <!-- Global theme overrides — MUST be last to override hardcoded colors -->
    <link rel="stylesheet" href="{{ asset('css/admin/theme.css') }}">
</head>

<body>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">

        <div class="sb-brand">
            <div class="sb-logo">
                <img src="{{ asset('logo.png') }}" alt="CampusSupply Logo" class="sb-logo-img">
            </div>
            <div class="sb-brand-text">
                <strong>CampusSupply</strong>
                <small>Admin Portal</small>
            </div>
        </div>

        <div class="sb-toggle" id="sidebarToggle" onclick="toggleSidebar()">
            <i class="fa-solid fa-chevron-left"></i>
        </div>

        <nav class="sb-nav">
            <div class="sb-section-label">Overview</div>

            <a href="{{ route('admin.dashboard') }}"
                class="sb-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="sb-item-icon fa-solid fa-house"></i>
                <span class="sb-item-label">Dashboard</span>
                <span class="sb-tooltip">Dashboard</span>
            </a>

            <div class="sb-divider"></div>
            <div class="sb-section-label">Catalog</div>

            <a href="{{ route('admin.items.index') }}"
                class="sb-item {{ request()->routeIs('admin.items.*') ? 'active' : '' }}">
                <i class="sb-item-icon fa-solid fa-box-open"></i>
                <span class="sb-item-label">All Products</span>
                <span class="sb-tooltip">All Products</span>
            </a>
            <a href="{{ route('admin.categories.index') }}"
                class="sb-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="sb-item-icon fa-solid fa-folder-open"></i>
                <span class="sb-item-label">Categories</span>
                <span class="sb-tooltip">Categories</span>
            </a>
            <a href="{{ route('admin.brands.index') }}"
                class="sb-item {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                <i class="sb-item-icon fa-solid fa-copyright"></i>
                <span class="sb-item-label">Brands</span>
                <span class="sb-tooltip">Brands</span>
            </a>
            <a href="{{ route('admin.types.index') }}"
                class="sb-item {{ request()->routeIs('admin.types.*') ? 'active' : '' }}">
                <i class="sb-item-icon fa-solid fa-tag"></i>
                <span class="sb-item-label">Types</span>
                <span class="sb-tooltip">Types</span>
            </a>

            @if(in_array(auth()->user()->role_id, [1, 3, 5]))
            <div class="sb-divider"></div>
            <div class="sb-section-label">Sales & Orders</div>

            <a href="{{ route('admin.orders.index') }}" class="sb-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="sb-item-icon fa-solid fa-receipt"></i>
                <span class="sb-item-label">Orders</span>
                <span class="sb-tooltip">Orders</span>
            </a>
            <a href="{{ route('admin.customers.index') }}" class="sb-item {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i class="sb-item-icon fa-solid fa-users"></i>
                <span class="sb-item-label">Customers</span>
                <span class="sb-tooltip">Customers</span>
            </a>
            @endif

            <div class="sb-divider"></div>
            <div class="sb-section-label">Store</div>


            <a href="{{ route('admin.banners.index') }}"
                class="sb-item {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                <i class="sb-item-icon fa-solid fa-image"></i>
                <span class="sb-item-label">Banners</span>
                <span class="sb-tooltip">Banners</span>
            </a>

            <a href="{{ route('admin.staff.index') }}"
                class="sb-item {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                <i class="sb-item-icon fa-solid fa-users-gear"></i>
                <span class="sb-item-label">Staff</span>
                <span class="sb-tooltip">Staff</span>
            </a>

        </nav>

        <div class="sb-bottom" id="sbBottom">
            <div class="sb-popup" id="sbPopup">
                <a class="pop-item" href="#">
                    <i class="pop-item-icon fa-regular fa-circle-user"></i>
                    <span class="pop-item-label">My Profile</span>
                </a>

                <div class="pop-divider"></div>

                <div class="pop-item" onclick="event.stopPropagation(); toggleTheme()">
                    <i class="pop-item-icon fa-solid fa-moon"></i>
                    <span class="pop-item-label">Dark Mode</span>
                    <label class="toggle-switch" onclick="event.stopPropagation(); toggleTheme(); return false;">
                        <input type="checkbox" id="darkModeSwitch">
                        <div class="toggle-track"></div>
                    </label>
                </div>

                <div class="pop-divider"></div>

                <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display: none;">
                    @csrf
                </form>
                <a class="pop-item pop-signout" href="#"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="pop-item-icon fa-solid fa-arrow-right-from-bracket"></i>
                    <span class="pop-item-label">Sign Out</span>
                </a>
            </div>

            <div class="sb-user" id="sbUserTrigger" onclick="toggleProfilePopup(event)">
                <div class="sb-avatar-wrap">
                    <div class="sb-avatar">
                        {{ strtoupper(substr(Auth::user()->staff?->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="sb-online-dot"></div>
                </div>
                <div class="sb-user-info">
                    <div class="sb-user-name">{{ Auth::user()->staff?->name ?? 'Super Admin' }}</div>
                    <div class="sb-user-role">{{ Auth::user()->role->name ?? 'Super Admin' }}</div>
                </div>
                <i class="fa-solid fa-chevron-up sb-chevron" id="sbChevron"></i>
            </div>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <div class="topbar">
            <h1 id="page-title">@yield('header_title', 'Welcome Back, Admin!')</h1>

            <div class="topbar-actions">
                <div class="notification-bell">
                    <i class="fa-regular fa-bell bell-icon"></i>
                    <span class="notification-badge">2</span>
                </div>
                
                <div class="profile-menu" id="topbarProfileTrigger" onclick="toggleTopbarProfile(event)">
                    <div class="avatar">{{ strtoupper(substr(Auth::user()->staff?->name ?? 'A', 0, 1)) }}</div>
                    <div class="sb-user-info">
                        <div class="sb-user-name">{{ Auth::user()->staff?->name ?? 'Super Admin' }}</div>
                        <div class="sb-user-role">{{ Auth::user()->role->name ?? 'Super Admin' }}</div>
                    </div>
                    
                    <!-- Topbar Profile Dropdown -->
                    <div class="topbar-popup" id="topbarPopup">
                        <a class="pop-item" href="#">
                            <i class="pop-item-icon fa-regular fa-circle-user"></i>
                            <span class="pop-item-label">My Profile</span>
                        </a>
                        <div class="pop-divider"></div>
                        <a class="pop-item pop-signout" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="pop-item-icon fa-solid fa-arrow-right-from-bracket"></i>
                            <span class="pop-item-label">Sign Out</span>
                        </a>
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
    <script src="{{ asset('js/admin/sidebar_rbac.js') }}"></script>
    <script src="{{ asset('js/admin/live_search.js?v=' . time()) }}"></script>
    @stack('scripts')
</body>

</html>