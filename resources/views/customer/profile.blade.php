@extends('layouts.customer')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/customer/views/profile.css') }}">
@endpush

@section('title', 'My Profile - Campus Supply')



@section('content')
    <div class="profile-container">
        <!-- Sidebar -->
        <aside class="profile-sidebar">
            <div class="user-card">
                <div class="user-avatar">
                    @if($customer->image)
                        <img src="{{ asset('storage/' . $customer->image) }}" alt="Profile">
                    @else
                        <i class="fa-solid fa-user"></i>
                    @endif
                </div>
                <div class="user-name">{{ $customer->name ?? 'User' }}</div>
                <div class="user-email">{{ Auth::user()->email }}</div>
            </div>

            <div class="profile-menu">
                <div class="menu-item active" onclick="showSection('orders')">
                    <i class="fa-solid fa-box"></i> Order History
                </div>
                <div class="menu-item" onclick="showSection('wishlist')">
                    <i class="fa-regular fa-heart"></i> Wishlist
                </div>
                <div class="menu-item" onclick="showSection('addresses')">
                    <i class="fa-solid fa-location-dot"></i> Saved Addresses
                </div>
                <div class="menu-item" onclick="showSection('account')">
                    <i class="fa-solid fa-gear"></i> Account Settings
                </div>
                <a href="{{ route('logout') }}" class="menu-item logout-btn">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="profile-main">
            @if(session('success'))
                <div class="alert alert-success"><i class="fa-solid fa-check-circle"></i> {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error"><i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}</div>
            @endif

            <!-- Order History -->
            <div id="orders-section" class="section-box active">
                <h2 class="section-title">Recent Orders</h2>
                @if($orders->isEmpty())
                    <div class="empty-state">
                        <i class="fa-solid fa-box-open"></i>
                        <h3>No orders yet</h3>
                        <p>Start shopping to see your orders here.</p>
                        <a href="{{ route('shop.index') }}" class="inline-style-105">Start Shopping</a>
                    </div>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    <td>{{ number_format($order->total_amount) }} Ks</td>
                                    <td><span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                                    </td>
                                    <td><a href="{{ route('profile.order-detail', $order->id) }}" class="btn-view">View Details</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <!-- Wishlist -->
            <div id="wishlist-section" class="section-box">
                <h2 class="section-title">My Wishlist</h2>
                @if($wishlistItems->isEmpty())
                    <div class="empty-state">
                        <i class="fa-regular fa-heart"></i>
                        <h3>Your wishlist is empty</h3>
                        <p>Start adding items you love.</p>
                        <a href="{{ route('shop.index') }}" class="inline-style-106">Browse Products</a>
                    </div>
                @else
                    <div class="wishlist-grid">
                        @foreach($wishlistItems as $wish)
                            @if($wish->item)
                                <div class="wishlist-card">
                                    <form action="{{ route('profile.remove-wishlist', $wish->id) }}" method="POST"
                                        style="position:absolute; top:10px; right:10px;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="wishlist-remove" title="Remove"><i
                                                class="fa-solid fa-times"></i></button>
                                    </form>
                                    <img src="{{ $wish->item->images->first() ? asset('storage/' . $wish->item->images->first()->image_path) : asset('images/placeholder.jpg') }}"
                                        alt="{{ $wish->item->name }}">
                                    <div class="wishlist-name">{{ Str::limit($wish->item->name, 30) }}</div>
                                    <div class="wishlist-price">{{ number_format($wish->item->price) }} Ks</div>
                                    <div class="wishlist-actions">
                                        <a href="{{ route('shop.show', $wish->item->id) }}" class="btn-view-detail">View</a>
                                        <form action="{{ route('cart.add-item', $wish->item->id) }}" method="POST" style="flex:1;">
                                            @csrf
                                            <button class="btn-add-cart-wl inline-style-107" type="submit">
                                                <i class="fa-solid fa-cart-shopping"></i> Add to Cart
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Saved Addresses -->
            <div id="addresses-section" class="section-box">
                <div class="inline-style-108">
                    <h2 class="section-title inline-style-109">Shipping Addresses</h2>
                    <button class="btn-add-address" onclick="toggleModal('addressModal')"><i class="fa-solid fa-plus"></i>
                        Add New</button>
                </div>

                @if($addresses->isEmpty())
                    <div class="empty-state">
                        <i class="fa-solid fa-location-dot"></i>
                        <p>No saved addresses yet.</p>
                    </div>
                @else
                    <div class="address-grid-2col">
                        @foreach($addresses as $address)
                            <div class="address-card {{ $address->is_default ? 'default' : '' }}">
                                @if($address->is_default)
                                    <span class="default-badge">Default</span>
                                @endif
                                <div class="address-name">{{ $address->label ?? 'Address' }}</div>
                                <div class="address-text">
                                    {{ $address->address_line }}<br>
                                    {{ $address->city }}<br>
                                    Phone: {{ $address->phone }}
                                </div>
                                <div class="address-actions">
                                    <button
                                        onclick="editAddress({{ $address->id }}, '{{ addslashes($address->label) }}', '{{ addslashes($address->address_line) }}', '{{ addslashes($address->city) }}', '{{ addslashes($address->phone) }}')">Edit</button>
                                    @if(!$address->is_default)
                                        <form action="{{ route('profile.set-default-address', $address->id) }}" method="POST">
                                            @csrf
                                            <button type="submit">Set Default</button>
                                        </form>
                                        <form action="{{ route('profile.delete-address', $address->id) }}" method="POST"
                                            onsubmit="return confirm('Delete this address?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="del-btn">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Account Settings -->
            <div id="account-section" class="section-box">
                <h2 class="section-title">Account Settings</h2>

                <!-- Profile Image -->
                <div class="profile-image-row">
                    <div class="profile-img-circle">
                        @if($customer->image)
                            <img src="{{ asset('storage/' . $customer->image) }}" alt="Profile">
                        @else
                            <i class="fa-solid fa-user"></i>
                        @endif
                    </div>
                    <form action="{{ route('profile.update-image') }}" method="POST" enctype="multipart/form-data"
                        class="inline-style-110">
                        @csrf
                        <input type="file" name="image" id="profileImageInput" accept="image/*" class="inline-style-111"
                            onchange="this.form.submit()">
                        <button type="button" class="btn-change-photo"
                            onclick="document.getElementById('profileImageInput').click()">
                            <i class="fa-solid fa-camera"></i> Change Photo
                        </button>
                    </form>
                </div>

                <!-- Profile Info Form -->
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    <div class="form-row-2">
                        <div class="form-group-p">
                            <label>Full Name</label>
                            <input type="text" name="name" value="{{ $customer->name ?? '' }}" required>
                        </div>
                        <div class="form-group-p">
                            <label>Email</label>
                            <input type="email" value="{{ Auth::user()->email }}" disabled>
                            <small class="inline-style-112">Email cannot be changed.</small>
                        </div>
                    </div>
                    <div class="form-row-2">
                        <div class="form-group-p">
                            <label>Phone</label>
                            <input type="text" name="phone" value="{{ $customer->phone ?? '' }}">
                        </div>
                        <div class="form-group-p">
                            <label>Date of Birth</label>
                            <input type="date" name="dob"
                                value="{{ $customer->dob ? \Carbon\Carbon::parse($customer->dob)->format('Y-m-d') : '' }}">
                        </div>
                    </div>
                    <div class="form-group-p inline-style-113">
                        <label>Gender</label>
                        <select name="gender">
                            <option value="">Select Gender</option>
                            <option value="male" {{ ($customer->gender ?? '') === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ ($customer->gender ?? '') === 'female' ? 'selected' : '' }}>Female
                            </option>
                            <option value="other" {{ ($customer->gender ?? '') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-save-p">Save Changes</button>
                </form>

                <hr class="divider">

                <!-- Change Password -->
                <h3 class="inline-style-114">Change Password</h3>
                <form action="{{ route('profile.change-password') }}" method="POST">
                    @csrf
                    <div class="form-group-p">
                        <label>Current Password</label>
                        <input type="password" name="current_password" required>
                    </div>
                    <div class="form-row-2">
                        <div class="form-group-p">
                            <label>New Password</label>
                            <input type="password" name="password" required minlength="8">
                            <small class="inline-style-115">Minimum 8 characters.</small>
                        </div>
                        <div class="form-group-p">
                            <label>Confirm New Password</label>
                            <input type="password" name="password_confirmation" required minlength="8">
                        </div>
                    </div>
                    <button type="submit" class="btn-save-p">Update Password</button>
                </form>
            </div>
        </main>
    </div>

    <!-- Add Address Modal -->
    <div id="addressModal" class="modal">
        <div class="modal-content">
            <button class="modal-close" onclick="toggleModal('addressModal')"><i class="fa-solid fa-times"></i></button>
            <div class="modal-title"><i class="fa-solid fa-location-dot inline-style-116"></i> Add Shipping Address</div>
            <form action="{{ route('profile.add-address') }}" method="POST" id="addressForm">
                @csrf
                <input type="hidden" name="address_id" id="editAddressId" value="">
                <div class="form-group-p">
                    <label>Label / Address Name</label>
                    <input type="text" name="label" id="addressLabel" placeholder="e.g. Home, Office">
                </div>
                <div class="form-group-p">
                    <label>Detailed Address (No, Street, Ward) *</label>
                    <textarea name="address_line" id="addressLine" rows="2" required placeholder="No.12, Zay Street..."
                        class="inline-style-117"></textarea>
                </div>
                <div class="form-row-2">
                    <div class="form-group-p">
                        <label>Township</label>
                        <input type="text" name="township" id="addressTownship" placeholder="e.g. Kamayut">
                    </div>
                    <div class="form-group-p">
                        <label>City / Region *</label>
                        <input type="text" name="city" id="addressCity" required list="profileRegionOptions"
                            placeholder="Select or type region...">
                        <datalist id="profileRegionOptions">
                            <option value="Yangon">
                            <option value="Mandalay">
                            <option value="Naypyidaw">
                            <option value="Bago">
                            <option value="Shan State">
                        </datalist>
                    </div>
                </div>
                <div class="form-group-p">
                    <label>Phone *</label>
                    <input type="tel" name="phone" id="addressPhone" required placeholder="09xxxxxxxxx">
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-modal-cancel" onclick="toggleModal('addressModal')">Cancel</button>
                    <button type="submit" class="btn-modal-save">Save Address</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Section switching
        function showSection(section) {
            document.querySelectorAll('.section-box').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.menu-item').forEach(el => el.classList.remove('active'));

            document.getElementById(section + '-section').classList.add('active');

            // Set active menu
            const menuItems = document.querySelectorAll('.menu-item');
            const sectionMap = { orders: 0, wishlist: 1, addresses: 2, account: 3 };
            menuItems[sectionMap[section]].classList.add('active');
        }

        // Modal
        function toggleModal(id) {
            const modal = document.getElementById(id);
            modal.classList.toggle('show');
            if (!modal.classList.contains('show')) {
                // Reset form
                document.getElementById('editAddressId').value = '';
                document.getElementById('addressLabel').value = '';
                document.getElementById('addressLine').value = '';
                document.getElementById('addressTownship').value = '';
                document.getElementById('addressCity').value = '';
                document.getElementById('addressPhone').value = '';
            }
        }

        // Edit address — pre-fill modal
        function editAddress(id, label, line, city, phone) {
            document.getElementById('editAddressId').value = id;
            document.getElementById('addressLabel').value = label;
            document.getElementById('addressLine').value = line;
            document.getElementById('addressCity').value = city;
            document.getElementById('addressPhone').value = phone;
            toggleModal('addressModal');
        }

        // Close modal on backdrop click
        document.getElementById('addressModal').addEventListener('click', function (e) {
            if (e.target === this) toggleModal('addressModal');
        });

        // Show correct section on load based on URL hash
        const hash = window.location.hash;
        if (hash === '#wishlist') showSection('wishlist');
        else if (hash === '#addresses') showSection('addresses');
        else if (hash === '#account') showSection('account');
    </script>
@endpush