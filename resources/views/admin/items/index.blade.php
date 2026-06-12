@extends('layouts.admin')

@section('title', 'Items Management')
@section('header_title', 'All Items')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/items_index.css') }}">
@endpush

@section('content')
    <div class="item-card">
        <div class="card-header">
        <h2><i class="fa-solid fa-box-open"></i> All Products</h2>
        <div class="header-actions">
            <form action="{{ route('admin.items.index') }}" method="GET" class="search-form live-search-form">
                <i class="fa-solid fa-search search-icon"></i>
                <input type="text" name="search" placeholder="Search items..." value="{{ request('search') }}">
                
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
                        <div class="filter-section">
                            <span class="filter-label">Stock</span>
                            <label class="filter-option">
                                <input type="radio" name="stock" value="all" onchange="applyFilters()" {{ request('stock') == 'all' || !request('stock') ? 'checked' : '' }}> All Stock Levels
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="stock" value="in_stock" onchange="applyFilters()" {{ request('stock') == 'in_stock' ? 'checked' : '' }}> In Stock (> 5)
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="stock" value="low_stock" onchange="applyFilters()" {{ request('stock') == 'low_stock' ? 'checked' : '' }}> Low Stock (1 - 5)
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="stock" value="out_of_stock" onchange="applyFilters()" {{ request('stock') == 'out_of_stock' ? 'checked' : '' }}> Out of Stock (0)
                            </label>
                        </div>
                    </div>
                </div>
            </form>
            <a href="{{ route('admin.items.create') }}" class="btn-primary">
                <i class="fa-solid fa-plus"></i> Add New Product
            </a>
        </div>
    </div>

        @if(session('success'))
            <div class="alert-success">
                <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div id="tableDataContainer">
            <div class="table-responsive">
                <table class="item-table">
                    <thead>
                        <tr>
                            <th>Item ID</th>
                            <th>Image</th>
                            <th>Name & Type</th>
                            <th>Brand</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td class="id-column">ITM-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    @if($item->image)
                                        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="item-image-small">
                                    @else
                                        <div class="item-image-placeholder">
                                            <i class="fa-solid fa-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="item-name">{{ $item->name }}</div>
                                    <div class="item-category">{{ $item->type->name ?? 'No Type' }}</div>
                                </td>
                                <td>
                                    @if($item->brand)
                                        <span class="brand-badge">{{ $item->brand->name }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ number_format($item->price, 2) }} MMK</td>
                                <td>
                                    @if($item->stock_quantity > 5)
                                        <span class="stock-badge in-stock"><i class="fa-solid fa-check"></i> {{ $item->stock_quantity }} In Stock</span>
                                    @elseif($item->stock_quantity > 0)
                                        <span class="stock-badge low-stock"><i class="fa-solid fa-triangle-exclamation"></i> {{ $item->stock_quantity }} Low</span>
                                    @else
                                        <span class="stock-badge out-stock"><i class="fa-solid fa-xmark"></i> Out of Stock</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->status === 'active')
                                        <span class="status-badge status-active"><i class="fa-solid fa-circle-check"></i> Active</span>
                                    @else
                                        <span class="status-badge status-inactive"><i class="fa-solid fa-circle-xmark"></i> Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.items.show', $item->id) }}" class="btn-icon btn-view"
                                            title="View Details">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.items.edit', $item->id) }}" class="btn-icon btn-edit"
                                            title="Edit Item">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <form action="{{ route('admin.items.destroy', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this item?');"
                                            class="form-inline">
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
                                <td colspan="8" class="empty-state">
                                    <div class="empty-state-icon"><i class="fa-solid fa-box-open"></i></div>
                                    <h3>No Items Found</h3>
                                    <p>Your inventory is empty. Start adding items.</p>
                                    <a href="{{ route('admin.items.create') }}" class="btn-outline mt-3">Add First Item</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($items instanceof \Illuminate\Pagination\LengthAwarePaginator && $items->hasPages())
                <div class="pagination-container">
                    {{ $items->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection