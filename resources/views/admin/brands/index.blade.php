@extends('layouts.admin')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/views/brands.css') }}">
@endpush


@section('title', 'Brands Management')
@section('header_title', 'Product Brands')



@section('content')
<div class="brand-card">
    <div class="card-header">
        <h2><i class="fa-solid fa-copyright"></i> Store Brands</h2>
        <div class="header-actions">
            <form action="{{ route('admin.brands.index') }}" method="GET" class="search-form live-search-form">
                <i class="fa-solid fa-search search-icon"></i>
                <input type="text" name="search" placeholder="Search brands..." value="{{ request('search') }}">
                
                <!-- Custom Dropdown Filter -->
                <div class="custom-dropdown">
                    <button type="button" class="btn-filter dropdown-toggle">
                        <i class="fa-solid fa-sliders"></i> Filter
                    </button>
                    <div class="custom-dropdown-menu">
                        <div class="filter-section">
                            <span class="filter-label">Sort By</span>
                            <label class="filter-option">
                                <input type="radio" name="sort" value="newest" onchange="applyFilters()" {{ request('sort') == 'newest' ? 'checked' : '' }}> Newest First
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="sort" value="oldest" onchange="applyFilters()" {{ request('sort') == 'oldest' || !request('sort') ? 'checked' : '' }}> Oldest First
                            </label>
                        </div>
                        <div class="filter-section">
                            <span class="filter-label">Status</span>
                            <label class="filter-option">
                                <input type="radio" name="status" value="all" onchange="applyFilters()" {{ request('status') == 'all' || !request('status') ? 'checked' : '' }}> All Statuses
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="status" value="active" onchange="applyFilters()" {{ request('status') == 'active' ? 'checked' : '' }}> Active
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="status" value="inactive" onchange="applyFilters()" {{ request('status') == 'inactive' ? 'checked' : '' }}> Inactive
                            </label>
                        </div>
                    </div>
                </div>
            </form>
            <button class="btn-primary" onclick="openModal('addBrandModal')">
                <i class="fa-solid fa-plus"></i> Add New Brand
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert-error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div id="tableDataContainer">
        <div class="table-responsive">
            <table class="brand-table">
                <thead>
                    <tr>
                        <th>Brand ID</th>
                        <th>Logo</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($brands as $brand)
                        <tr>
                            <td class="id-column">BRD-{{ str_pad($brand->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                @if($brand->logo)
                                    <img src="{{ Storage::url($brand->logo) }}" alt="{{ $brand->name }}" class="brand-logo-small">
                                @else
                                    <div class="brand-logo-placeholder">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td><div class="brand-name">{{ $brand->name }}</div></td>
                            <td>
                                @if($brand->status === 'active')
                                    <span class="status-badge status-active"><i class="fa-solid fa-circle-check"></i> Active</span>
                                @else
                                    <span class="status-badge status-inactive"><i class="fa-solid fa-circle-xmark"></i> Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button onclick="editBrand({{ $brand->id }}, '{{ htmlspecialchars($brand->name, ENT_QUOTES) }}', '{{ $brand->status }}')" class="btn-icon btn-edit" title="Edit Brand">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" class="form-inline" onsubmit="event.preventDefault(); showConfirmModal('Are you sure you want to delete this brand?', () => this.submit());">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon btn-delete" title="Delete Brand">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <div class="empty-state-icon"><i class="fa-solid fa-copyright"></i></div>
                                <h3>No Brands Found</h3>
                                <p>You haven't added any brands yet.</p>
                                <button class="btn-outline mt-3" onclick="openModal('addBrandModal')">Add First Brand</button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($brands instanceof \Illuminate\Pagination\LengthAwarePaginator && $brands->hasPages())
            <div class="pagination-container">
                {{ $brands->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Add Brand Modal -->
<div class="modal" id="addBrandModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New Brand</h3>
            <button class="btn-close" onclick="closeModal('addBrandModal')"><i class="fa-solid fa-times"></i></button>
        </div>
        <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="name">Brand Name <span class="text-required">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" required placeholder="e.g. Pilot">
                </div>
                <div class="form-group">
                    <label for="logo">Brand Logo</label>
                    <input type="file" id="logo" name="logo" class="form-control" accept="image/*">
                    <small class="text-muted">Recommended size: 200x200px. Max size: 2MB.</small>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeModal('addBrandModal')">Cancel</button>
                <button type="submit" class="btn-primary">Save Brand</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Brand Modal -->
<div class="modal" id="editBrandModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Brand</h3>
            <button class="btn-close" onclick="closeModal('editBrandModal')"><i class="fa-solid fa-times"></i></button>
        </div>
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label for="edit_name">Brand Name <span class="text-required">*</span></label>
                    <input type="text" id="edit_name" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="edit_logo">Brand Logo</label>
                    <input type="file" id="edit_logo" name="logo" class="form-control" accept="image/*">
                    <small class="text-muted">Upload a new image to replace the current logo.</small>
                </div>
                <div class="form-group">
                    <label for="edit_status">Status</label>
                    <select name="status" id="edit_status" class="form-control">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeModal('editBrandModal')">Cancel</button>
                <button type="submit" class="btn-primary">Update Brand</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/admin/brands.js') }}"></script>
@endpush
