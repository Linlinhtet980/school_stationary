@extends('layouts.admin')

@section('title', 'Brands Management')
@section('header_title', 'Product Brands')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/brands.css') }}">
@endpush

@section('content')
<div class="brand-card">
    <div class="card-header">
        <h2><i class="fa-solid fa-copyright"></i> Store Brands</h2>
        <button class="btn-primary" onclick="openModal('addBrandModal')">
            <i class="fa-solid fa-plus"></i> Create Brand
        </button>
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

    <div class="table-responsive">
        <table class="brand-table">
            <thead>
                <tr>
                    <th>Brand ID</th>
                    <th>Logo</th>
                    <th>Brand Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($brands as $brand)
                <tr>
                    <td class="id-column">BRD-{{ str_pad($brand->id, 2, '0', STR_PAD_LEFT) }}</td>
                    <td>
                        @if($brand->logo)
                            <img src="{{ Storage::url($brand->logo) }}" alt="{{ $brand->name }}" class="brand-logo-small">
                        @else
                            <div class="brand-logo-placeholder">
                                <i class="fa-solid fa-image"></i>
                            </div>
                        @endif
                    </td>
                    <td class="brand-name">{{ $brand->name }}</td>
                    <td>
                        <div class="action-btns">
                            <button class="btn-icon btn-edit" onclick="openEditModal({{ $brand->id }}, '{{ addslashes($brand->name) }}')" title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this brand?');" class="form-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon btn-delete" title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="empty-state">
                        <div class="empty-state-icon"><i class="fa-solid fa-copyright"></i></div>
                        <h3>No Brands Found</h3>
                        <p>Start by creating your first product brand.</p>
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
