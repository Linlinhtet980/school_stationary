@extends('layouts.admin')

@section('title', 'Banners Management')
@section('header_title', 'Home Banners')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/banners_index.css') }}">
@endpush

@section('content')
<div class="banner-card">
    <div class="card-header">
        <h2><i class="fa-solid fa-images"></i> Homepage Banners</h2>
        <div class="header-actions">
            <form action="{{ route('admin.banners.index') }}" method="GET" class="search-form live-search-form">
                <i class="fa-solid fa-search search-icon"></i>
                <input type="text" name="search" placeholder="Search banners..." value="{{ request('search') }}">
                
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
            <a href="{{ route('admin.banners.create') }}" class="btn-primary"><i class="fa-solid fa-plus"></i> Add New Banner</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div id="tableDataContainer">
        <div class="table-responsive">
            <table class="banner-table">
                <thead>
                    <tr>
                        <th>Sequence</th>
                        <th>Banner Image</th>
                        <th>Title & Link</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($banners as $banner)
                        <tr>
                            <td class="order-column">
                                <div class="order-number">{{ $banner->sequence }}</div>
                            </td>
                            <td>
                                <div class="banner-img-container">
                                    <img src="{{ Storage::url($banner->image_path) }}" alt="{{ $banner->title }}" class="banner-img" style="height: 50px; border-radius: 6px; object-fit: cover;">
                                </div>
                            </td>
                            <td>
                                <div class="banner-title">{{ $banner->title ?: 'Untitled' }}</div>
                                @if($banner->link)
                                    <a href="{{ $banner->link }}" target="_blank" class="banner-link">
                                        <i class="fa-solid fa-link"></i> {{ Str::limit($banner->link, 30) }}
                                    </a>
                                @endif
                            </td>
                            <td>
                                @if($banner->status === 'active')
                                    <span class="status-badge status-active"><i class="fa-solid fa-circle-check"></i> Active</span>
                                @else
                                    <span class="status-badge status-inactive"><i class="fa-solid fa-circle-xmark"></i> Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn-icon btn-edit" title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this banner?');" class="form-inline">
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
                            <td colspan="5" class="empty-state">
                                <div class="empty-state-icon"><i class="fa-solid fa-images"></i></div>
                                <h3>No Banners Found</h3>
                                <p>There are no banners matching your criteria.</p>
                                <a href="{{ route('admin.banners.create') }}" class="btn-outline mt-3">Add First Banner</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($banners instanceof \Illuminate\Pagination\LengthAwarePaginator && $banners->hasPages())
            <div class="pagination-container">
                {{ $banners->links() }}
            </div>
        @endif
    </div>
</div>
@endsection