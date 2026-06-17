@extends('layouts.customer')

@section('title', 'My Profile - Campus Supply')

@section('content')
<div class="page-container">
    <div class="page-header">
        <h1>My Profile</h1>
        <p>Manage your account settings and preferences</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    <div class="profile-grid">
        <!-- Profile Information -->
        <div class="profile-card">
            <div class="card-header">
                <h2>Profile Information</h2>
            </div>
            
            <div class="profile-image-section">
                <div class="current-image">
                    @if($customer->image)
                        <img src="{{ asset('storage/' . $customer->image) }}" alt="Profile Image">
                    @else
                        <div class="no-profile-image">
                            <i class="fa-solid fa-user"></i>
                        </div>
                    @endif
                </div>
                <form action="{{ route('profile.update-image') }}" method="POST" enctype="multipart/form-data" class="image-upload-form">
                    @csrf
                    <input type="file" name="image" id="profileImage" accept="image/*" style="display: none;">
                    <button type="button" class="btn-upload" onclick="document.getElementById('profileImage').click()">
                        <i class="fa-solid fa-camera"></i> Change Photo
                    </button>
                    <button type="submit" class="btn-save-image hidden" id="saveImageBtn">Save</button>
                </form>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" class="profile-form">
                @csrf
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" value="{{ $customer->name ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" value="{{ Auth::user()->email }}" disabled>
                    <small>Email cannot be changed</small>
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" value="{{ $customer->phone ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" name="dob" value="{{ $customer->dob ? $customer->dob->format('Y-m-d') : '' }}">
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender">
                        <option value="">Select Gender</option>
                        <option value="male" {{ $customer->gender === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ $customer->gender === 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ $customer->gender === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <button type="submit" class="btn-save">Save Changes</button>
            </form>
        </div>

        <!-- Password Change -->
        <div class="profile-card">
            <div class="card-header">
                <h2>Change Password</h2>
            </div>
            
            <form action="{{ route('profile.change-password') }}" method="POST" class="password-form">
                @csrf
                <div class="form-group">
                    <label>Current Password</label>
                    <input type="password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="password" required minlength="8">
                    <small>Minimum 8 characters</small>
                </div>
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" name="password_confirmation" required minlength="8">
                </div>
                <button type="submit" class="btn-save">Update Password</button>
            </form>
        </div>

        <!-- Address Management -->
        <div class="profile-card addresses-card">
            <div class="card-header">
                <h2>Saved Addresses</h2>
                <button type="button" class="btn-add" onclick="showAddressForm()">
                    <i class="fa-solid fa-plus"></i> Add New
                </button>
            </div>

            <div class="address-list">
                @forelse($addresses as $address)
                    <div class="address-item {{ $address->is_default ? 'default' : '' }}">
                        <div class="address-content">
                            @if($address->label)
                                <div class="address-label">{{ $address->label }}</div>
                            @endif
                            <div class="address-line">{{ $address->address_line }}</div>
                            <div class="address-city">{{ $address->city }}</div>
                            <div class="address-phone">
                                <i class="fa-solid fa-phone"></i> {{ $address->phone }}
                            </div>
                            @if($address->is_default)
                                <span class="default-badge">Default</span>
                            @endif
                        </div>
                        <div class="address-actions">
                            @if(!$address->is_default)
                                <button type="button" class="btn-action btn-set-default" 
                                        onclick="setDefaultAddress({{ $address->id }})"
                                        title="Set as default">
                                    <i class="fa-solid fa-star"></i>
                                </button>
                            @endif
                            <button type="button" class="btn-action btn-edit" 
                                    onclick="editAddress({{ $address->id }}, '{{ $address->label }}', '{{ $address->address_line }}', '{{ $address->city }}', '{{ $address->phone }}')"
                                    title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            @if(!$address->is_default)
                                <button type="button" class="btn-action btn-delete" 
                                        onclick="deleteAddress({{ $address->id }})"
                                        title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="no-addresses">
                        <i class="fa-solid fa-location-dot"></i>
                        <p>No saved addresses</p>
                    </div>
                @endforelse
            </div>

            <!-- Add/Edit Address Form (Hidden by default) -->
            <div class="address-form-container" id="addressFormContainer" style="display: none;">
                <h3 id="addressFormTitle">Add New Address</h3>
                <form action="{{ route('profile.add-address') }}" method="POST" id="addressForm">
                    @csrf
                    <input type="hidden" name="address_id" id="editAddressId" value="">
                    <div class="form-group">
                        <label>Label (Optional)</label>
                        <input type="text" name="label" id="addressLabel" placeholder="e.g., Home, Office">
                    </div>
                    <div class="form-group">
                        <label>Address Line *</label>
                        <textarea name="address_line" id="addressLine" rows="2" required placeholder="Full address"></textarea>
                    </div>
                    <div class="form-group">
                        <label>City *</label>
                        <input type="text" name="city" id="addressCity" required placeholder="City">
                    </div>
                    <div class="form-group">
                        <label>Phone *</label>
                        <input type="text" name="phone" id="addressPhone" required placeholder="09xxxxxxxxx">
                    </div>
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="is_default" id="addressDefault"> Set as default address
                        </label>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-save">Save Address</button>
                        <button type="button" class="btn-cancel" onclick="hideAddressForm()">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.page-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.page-header {
    text-align: center;
    margin-bottom: 2rem;
}

.page-header h1 {
    font-size: 2rem;
    color: var(--secondary);
    margin-bottom: 0.5rem;
}

.page-header p {
    color: #666;
}

.alert {
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.profile-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
}

.profile-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 2px solid var(--primary);
}

.card-header h2 {
    color: var(--secondary);
    font-size: 1.25rem;
    margin: 0;
}

.profile-image-section {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1.5rem;
    border-bottom: 1px solid #eee;
}

.current-image img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
}

.no-profile-image {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #999;
    font-size: 2rem;
}

.image-upload-form {
    display: flex;
    gap: 0.5rem;
}

.btn-upload {
    padding: 0.5rem 1rem;
    background: var(--secondary);
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 0.9rem;
    cursor: pointer;
}

.btn-save-image {
    padding: 0.5rem 1rem;
    background: var(--primary);
    color: var(--secondary);
    border: none;
    border-radius: 6px;
    font-size: 0.9rem;
    cursor: pointer;
}

.btn-save-image.hidden {
    display: none;
}

.profile-form,
.password-form {
    padding: 1.5rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: var(--secondary);
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 0.95rem;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary);
}

.form-group input:disabled {
    background: #f5f5f5;
    cursor: not-allowed;
}

.form-group small {
    color: #666;
    font-size: 0.8rem;
}

.btn-save {
    width: 100%;
    padding: 0.75rem;
    background: var(--secondary);
    color: white;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.3s;
}

.btn-save:hover {
    background: #091a3a;
}

.btn-add {
    padding: 0.5rem 1rem;
    background: var(--primary);
    color: var(--secondary);
    border: none;
    border-radius: 6px;
    font-size: 0.9rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.addresses-card {
    grid-column: span 2;
}

@media (max-width: 768px) {
    .addresses-card {
        grid-column: span 1;
    }
}

.address-list {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.address-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    border: 2px solid transparent;
}

.address-item.default {
    border-color: var(--primary);
}

.address-content {
    flex: 1;
}

.address-label {
    font-weight: 600;
    color: var(--secondary);
    margin-bottom: 0.25rem;
}

.address-line {
    color: #333;
    margin-bottom: 0.25rem;
}

.address-city {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.address-phone {
    color: #666;
    font-size: 0.85rem;
}

.default-badge {
    display: inline-block;
    background: var(--primary);
    color: var(--secondary);
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-left: 0.5rem;
}

.address-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-action {
    width: 36px;
    height: 36px;
    border: 1px solid #ddd;
    border-radius: 6px;
    background: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
}

.btn-action:hover {
    border-color: var(--primary);
    background: #fffbeb;
}

.btn-action.btn-delete:hover {
    border-color: #dc3545;
    background: #fee2e2;
    color: #dc3545;
}

.no-addresses {
    text-align: center;
    padding: 2rem;
    color: #999;
}

.no-addresses i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.address-form-container {
    padding: 1.5rem;
    background: #f8f9fa;
    border-top: 1px solid #eee;
}

.address-form-container h3 {
    color: var(--secondary);
    margin-bottom: 1rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
}

.btn-cancel {
    padding: 0.75rem 1.5rem;
    background: #f5f5f5;
    color: #666;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
}
</style>

<script>
// Profile image preview
document.getElementById('profileImage').addEventListener('change', function(e) {
    if (e.target.files[0]) {
        document.getElementById('saveImageBtn').classList.remove('hidden');
    }
});

// Address form functions
function showAddressForm() {
    document.getElementById('addressFormContainer').style.display = 'block';
    document.getElementById('addressFormTitle').textContent = 'Add New Address';
    document.getElementById('addressForm').action = '{{ route('profile.add-address') }}';
    document.getElementById('editAddressId').value = '';
    document.getElementById('addressLabel').value = '';
    document.getElementById('addressLine').value = '';
    document.getElementById('addressCity').value = '';
    document.getElementById('addressPhone').value = '';
    document.getElementById('addressDefault').checked = false;
}

function hideAddressForm() {
    document.getElementById('addressFormContainer').style.display = 'none';
}

function editAddress(id, label, addressLine, city, phone) {
    document.getElementById('addressFormContainer').style.display = 'block';
    document.getElementById('addressFormTitle').textContent = 'Edit Address';
    document.getElementById('addressForm').action = '{{ route('profile.update-address', ':id') }}'.replace(':id', id);
    document.getElementById('editAddressId').value = id;
    document.getElementById('addressLabel').value = label;
    document.getElementById('addressLine').value = addressLine;
    document.getElementById('addressCity').value = city;
    document.getElementById('addressPhone').value = phone;
}

function setDefaultAddress(id) {
    if (confirm('Set this address as default?')) {
        fetch('{{ route('profile.set-default-address', ':id') }}'.replace(':id', id), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({})
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}

function deleteAddress(id) {
    if (confirm('Are you sure you want to delete this address?')) {
        fetch('{{ route('profile.delete-address', ':id') }}'.replace(':id', id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            }
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}
</script>
@endsection