@extends('layouts.admin')

@section('title', 'Types Management')
@section('header_title', 'Product Types')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/types.css') }}">
@endpush

@section('content')
<div class="type-card">
    <div class="card-header">
        <h2><i class="fa-solid fa-layer-group"></i> Store Types</h2>
        <button class="btn-primary" onclick="openModal('addTypeModal')">
            <i class="fa-solid fa-plus"></i> Create Type
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
        <table class="type-table">
            <thead>
                <tr>
                    <th>Type ID</th>
                    <th>Type Name</th>
                    <th>Category</th>
                    <th>Total Items</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($types as $type)
                <tr>
                    <td class="id-column">TYP-{{ str_pad($type->id, 2, '0', STR_PAD_LEFT) }}</td>
                    <td class="type-name">{{ $type->name }}</td>
                    <td>
                        <span class="category-badge">{{ $type->category->name ?? 'Unknown' }}</span>
                    </td>
                    <td class="item-count">{{ $type->items_count ?? 0 }}</td>
                    <td>
                        @if($type->status === 'active')
                            <span class="badge badge-active">Active</span>
                        @else
                            <span class="badge badge-inactive">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-btns">
                            <button class="btn-icon btn-edit" onclick="openEditModal({{ $type->id }}, '{{ $type->category_id }}', '{{ addslashes($type->name) }}', '{{ $type->status }}')" title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <form action="{{ route('admin.types.destroy', $type->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this type?');" class="form-inline">
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
                    <td colspan="6" class="empty-state">
                        <div class="empty-state-icon"><i class="fa-solid fa-folder-open"></i></div>
                        <h3>No Types Found</h3>
                        <p>Start by creating your first product type.</p>
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
