<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - School Stationary</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Link to external CSS file instead of inline styles -->
    
    @stack('styles')

    <!-- Global theme overrides — MUST be last to override hardcoded colors -->
    <link rel="stylesheet" href="{{ asset('css/admin/views/global.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/admin/views/admin.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/admin/views/theme.css') }}?v={{ time() }}">
</head>

<body>

    <!-- ═══ Page Loading Overlay ═══ -->
    <div id="pageLoader">
        <div class="loader-content">
            <img src="{{ asset('logo.png') }}" alt="Loading..." class="loader-logo">
            <div class="loader-ring"></div>
            <span class="loader-label">Loading…</span>
        </div>
    </div>

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

            @if(in_array(auth()->user()->role_id, [1, 2]))
            <div class="sb-divider"></div>
            <div class="sb-dropdown-trigger" onclick="toggleSidebarDropdown(this)">
                <div class="sb-section-label">Catalog</div>
                <i class="fa-solid fa-chevron-down sb-dropdown-caret"></i>
            </div>
            <div class="sb-dropdown-menu">
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
            </div>
            @endif

            @if(in_array(auth()->user()->role_id, [1, 3, 4, 5]))
            <div class="sb-divider"></div>
            <div class="sb-dropdown-trigger" onclick="toggleSidebarDropdown(this)">
                <div class="sb-section-label">Sales & Orders</div>
                <i class="fa-solid fa-chevron-down sb-dropdown-caret"></i>
            </div>
            <div class="sb-dropdown-menu">
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
            </div>
            @endif

            <div class="sb-divider"></div>
            <div class="sb-dropdown-trigger" onclick="toggleSidebarDropdown(this)">
                <div class="sb-section-label">Store</div>
                <i class="fa-solid fa-chevron-down sb-dropdown-caret"></i>
            </div>
            <div class="sb-dropdown-menu">
                @if(in_array(auth()->user()->role_id, [1, 2]))
                <a href="{{ route('admin.banners.index') }}"
                    class="sb-item {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                    <i class="sb-item-icon fa-solid fa-image"></i>
                    <span class="sb-item-label">Banners</span>
                    <span class="sb-tooltip">Banners</span>
                </a>
                @endif

                @if(auth()->user()->role_id === 1)
                <a href="{{ route('admin.bundles.index') }}"
                    class="sb-item {{ request()->routeIs('admin.bundles.*') ? 'active' : '' }}">
                    <i class="sb-item-icon fa-solid fa-boxes-stacked"></i>
                    <span class="sb-item-label">Bundles</span>
                    <span class="sb-tooltip">Bundles</span>
                </a>
                @endif

                @if(in_array(auth()->user()->role_id, [1, 4]))
                <a href="{{ route('admin.reviews.index') }}"
                    class="sb-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                    <i class="sb-item-icon fa-solid fa-star-half-stroke"></i>
                    <span class="sb-item-label">Reviews</span>
                    <span class="sb-tooltip">Reviews</span>
                </a>
                @endif

                @if(auth()->user()->role_id === 1)
                <a href="{{ route('admin.staff.index') }}"
                    class="sb-item {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                    <i class="sb-item-icon fa-solid fa-users-gear"></i>
                    <span class="sb-item-label">Staff</span>
                    <span class="sb-tooltip">Staff</span>
                </a>
                @endif
            </div>

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

                <form method="POST" action="{{ route('logout') }}" id="logout-form" class="inline-style-121">
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
                    <div class="sb-user-role role-badge-{{ Str::slug(Auth::user()->role->name ?? 'super-admin') }}">{{ Auth::user()->role->name ?? 'Super Admin' }}</div>
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
                <div class="notification-bell" id="notificationTrigger" onclick="toggleNotifications(event)">
                    <i class="fa-regular fa-bell bell-icon"></i>
                    @php $unreadCount = Auth::user()->unreadNotifications->count(); @endphp
                    @if($unreadCount > 0)
                        <span class="notification-badge">{{ $unreadCount }}</span>
                    @endif
                    
                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="notification-header">
                            <h4>Notifications</h4>
                            @if($unreadCount > 0)
                                <a href="{{ route('admin.notifications.markAllRead') }}" class="mark-all-read">Mark all read</a>
                            @endif
                        </div>
                        <div class="notification-list">
                            @forelse(Auth::user()->unreadNotifications as $notification)
                                <a href="{{ $notification->data['link'] ?? '#' }}" class="notification-item unread">
                                    <div class="notification-icon {{ $notification->data['icon_bg'] ?? 'bg-primary' }}">
                                        <i class="{{ $notification->data['icon'] ?? 'fa-solid fa-bell' }}"></i>
                                    </div>
                                    <div class="notification-content">
                                        <p class="notification-message">{{ $notification->data['message'] }}</p>
                                        <span class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                </a>
                            @empty
                                <div class="notification-empty">
                                    <p>No new notifications</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
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

    <script src="{{ asset('js/layouts/admin.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/admin/sidebar_rbac.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/admin/live_search.js?v=' . time()) }}"></script>
    <script src="{{ asset('js/page-loader.js') }}?v={{ time() }}"></script>
    @stack('scripts')

</body>

</html>