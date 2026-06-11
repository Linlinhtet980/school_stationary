@extends('layouts.admin')

@section('title', 'Items Management')
@section('header_title', 'All Items')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/items_index.css') }}">
@endpush

@section('content')
    <div class="item-card">
        <div class="card-header">
            <h2><i class="fa-solid fa-box"></i> Inventory Items</h2>
            <a href="{{ route('admin.items.create') }}" class="btn-primary">
                <i class="fa-solid fa-plus"></i> Add New Item
            </a>
        </div>

        @if(session('success'))
            <div class="alert-success">
                <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

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
                                <div class="item-type"><i class="fa-solid fa-tags"></i> {{ $item->type->name ?? 'N/A' }}</div>
                            </td>
                            <td>
                                @if($item->brand)
                                    <span class="brand-badge">{{ $item->brand->name }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <!-- Price Column ပြသသည့်နေရာ -->
                            <td>
                                {{ $item->price_range }}
                            </td>

                            <!-- Stock Column ပြသသည့်နေရာ -->
                            <td>
                                @if($item->total_stock > 0)
                                    <span class="text-success" style="font-weight: bold;">
                                        {{ number_format($item->total_stock) }} Pcs
                                    </span>
                                @else
                                    <span class="text-danger" style="font-weight: bold;">Out of Stock</span>
                                @endif
                            </td>
                            <td>
                                @if($item->status === 'active')
                                    <span class="badge badge-active">Active</span>
                                @elseif($item->status === 'out_of_stock')
                                    <span class="badge badge-warning">Out of Stock</span>
                                @else
                                    <span class="badge badge-inactive">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-btns">
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
@endsection