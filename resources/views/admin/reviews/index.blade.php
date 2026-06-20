@extends('layouts.admin')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/views/reviews.css') }}">
@endpush




@section('title', 'Reviews Moderation')

@section('content')
<div class="page-header">
    <div class="header-left">
        <h1 class="page-title">Reviews Moderation</h1>
        <p class="page-subtitle">Manage and moderate customer reviews</p>
    </div>
    <div class="header-right">
        <div class="header-actions">
            <form action="{{ route('admin.reviews.index') }}" method="GET" class="search-form live-search-form">
                <i class="fa-solid fa-search"></i>
                <input type="text" name="search" placeholder="Search reviews..." value="{{ request('search') }}">
            </form>

            <div class="filter-dropdown">
                <button type="button" class="btn btn-secondary filter-toggle">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                <div class="filter-menu">
                    <form action="{{ route('admin.reviews.index') }}" method="GET" class="filter-form">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        
                        <div class="filter-group">
                            <label>Sort By ID</label>
                            <div class="filter-options">
                                <label class="filter-option">
                                    <input type="radio" name="sort" value="newest" {{ request('sort', 'newest') == 'newest' ? 'checked' : '' }}>
                                    Newest First
                                </label>
                                <label class="filter-option">
                                    <input type="radio" name="sort" value="oldest" {{ request('sort') == 'oldest' ? 'checked' : '' }}>
                                    Oldest First
                                </label>
                            </div>
                        </div>

                        <div class="filter-group">
                            <label>Status</label>
                            <div class="filter-options">
                                <label class="filter-option">
                                    <input type="radio" name="status" value="all" {{ request('status', 'all') == 'all' ? 'checked' : '' }}>
                                    All Reviews
                                </label>
                                <label class="filter-option">
                                    <input type="radio" name="status" value="visible" {{ request('status') == 'visible' ? 'checked' : '' }}>
                                    Visible
                                </label>
                                <label class="filter-option">
                                    <input type="radio" name="status" value="hidden" {{ request('status') == 'hidden' ? 'checked' : '' }}>
                                    Hidden
                                </label>
                            </div>
                        </div>

                        <div class="filter-actions">
                            <button type="button" class="btn btn-outline reset-filter">Reset</button>
                            <button type="submit" class="btn btn-primary">Apply</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <div id="tableDataContainer">
        @include('admin.reviews.partials.table', ['reviews' => $reviews])
    </div>
</div>
@endsection
