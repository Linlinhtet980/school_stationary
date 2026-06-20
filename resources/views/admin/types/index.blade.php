@extends('layouts.admin')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/views/types.css') }}">
@endpush


@section('title', 'Types Management')
@section('header_title', 'Product Types')



@section('content')
<div class="type-card">
    <div class="card-header">
        <h2><i class="fa-solid fa-layer-group"></i> Item Types</h2>
        <div class="header-actions">
            <form action="{{ route('admin.types.index') }}" method="GET" class="search-form live-search-form">
                <i class="fa-solid fa-search search-icon"></i>
                <input type="text" name="search" placeholder="Search types..." value="{{ request('search') }}">
                
                <!-- Custom Dropdown Filter -->
                <div class="custom-dropdown">
                    <button type="button" class="btn-filter dropdown-toggle">
                        <i class="fa-solid fa-sliders"></i> Filter
                    </button>
                    <div class="custom-dropdown-menu">
                        <div class="filter-section">
                            <span class="filter-label">Sort By</span>
                            <label class="filter-option">
                                <input type="radio" name="sort" value="newest" onchange="applyFilters()" {{ request('sort') == 'newest' || !request('sort') ? 'checked' : '' }}> Newest First
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="sort" value="oldest" onchange="applyFilters()" {{ request('sort') == 'oldest' ? 'checked' : '' }}> Oldest First
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
            <button class="btn-primary" onclick="openModal('addTypeModal')">
                <i class="fa-solid fa-plus"></i> Add New Type
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
            <table class="type-table">
                <thead>
                    <tr>
                        <th>Type ID</th>
                        <th>Name</th>
                        <th>Parent Category</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($types as $type)
                        <tr>
                            <td class="id-column">TYP-{{ str_pad($type->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td><div class="type-name">{{ $type->name }}</div></td>
                            <td>
                                @if($type->category)
                                    <span class="category-badge">{{ $type->category->name }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($type->status === 'active')
                                    <span class="status-badge status-active"><i class="fa-solid fa-circle-check"></i> Active</span>
                                @else
                                    <span class="status-badge status-inactive"><i class="fa-solid fa-circle-xmark"></i> Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button onclick="editType({{ $type->id }}, '{{ htmlspecialchars($type->name, ENT_QUOTES) }}', '{{ $type->category_id }}', '{{ htmlspecialchars($type->description, ENT_QUOTES) }}', '{{ $type->status }}')" class="btn-icon btn-edit" title="Edit Type">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <form action="{{ route('admin.types.destroy', $type->id) }}" method="POST" class="form-inline" onsubmit="return confirm('Are you sure you want to delete this item type?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon btn-delete" title="Delete Type">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <div class="empty-state-icon"><i class="fa-solid fa-layer-group"></i></div>
                                <h3>No Types Found</h3>
                                <p>You haven't added any item types yet.</p>
                                <button class="btn-outline mt-3" onclick="openModal('addTypeModal')">Add First Type</button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($types instanceof \Illuminate\Pagination\LengthAwarePaginator && $types->hasPages())
            <div class="pagination-container">
                {{ $types->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Add Type Modal -->
<div class="modal" id="addTypeModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New Type</h3>
            <button class="btn-close" onclick="closeModal('addTypeModal')"><i class="fa-solid fa-times"></i></button>
        </div>
        <form action="{{ route('admin.types.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="category_id">Category <span class="text-required">*</span></label>
                    <select id="category_id" name="category_id" class="form-control" required>
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">Type Name <span class="text-required">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" required placeholder="e.g. Pen">
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeModal('addTypeModal')">Cancel</button>
                <button type="submit" class="btn-primary">Save Type</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Type Modal -->
<div class="modal" id="editTypeModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Type</h3>
            <button class="btn-close" onclick="closeModal('editTypeModal')"><i class="fa-solid fa-times"></i></button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label for="edit_category_id">Category <span class="text-required">*</span></label>
                    <select id="edit_category_id" name="category_id" class="form-control" required>
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit_name">Type Name <span class="text-required">*</span></label>
                    <input type="text" id="edit_name" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="edit_status">Status</label>
                    <select id="edit_status" name="status" class="form-control">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeModal('editTypeModal')">Cancel</button>
                <button type="submit" class="btn-primary">Update Type</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/admin/types.js') }}"></script>
@endpush
