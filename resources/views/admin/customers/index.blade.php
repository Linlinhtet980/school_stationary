@extends('layouts.admin')

@section('title', 'Customers')
@section('header_title', 'Customers Management')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/customers.css') }}">
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <h2><i class="fa-solid fa-users"></i> Registered Customers</h2>
        <div class="header-actions">
            <form action="{{ route('admin.customers.index') }}" method="GET" class="search-form live-search-form">
                <i class="fa-solid fa-search search-icon"></i>
                <input type="text" name="search" placeholder="Search customers..." value="{{ request('search') }}">
                
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
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        <div id="tableDataContainer">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Registered At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                <td>#CUST-{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    <div class="customer-profile-container">
                                        @if($customer->image)
                                            <img src="{{ Storage::url($customer->image) }}" alt="{{ $customer->name }}" class="customer-avatar">
                                        @else
                                            <div class="customer-avatar-placeholder">
                                                <i class="fa-solid fa-user"></i>
                                            </div>
                                        @endif
                                        <span class="customer-name">{{ $customer->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $customer->user->email ?? 'N/A' }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>
                                    @if($customer->user && $customer->user->status === 'blocked')
                                        <span class="badge badge-blocked">Blocked</span>
                                    @else
                                        <span class="badge badge-active">Active</span>
                                    @endif
                                </td>
                                <td>{{ $customer->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="flex-gap-8">
                                        <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn-icon btn-view" title="View Details">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        @if($customer->user)
                                            <form action="{{ route('admin.customers.block', $customer->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to {{ $customer->user->status === 'blocked' ? 'unblock' : 'block' }} this customer?');">
                                                @csrf
                                                <button type="submit" class="btn-icon {{ $customer->user->status === 'blocked' ? 'btn-unblock-action' : 'btn-block-action' }}" title="{{ $customer->user->status === 'blocked' ? 'Unblock Customer' : 'Block Customer' }}">
                                                    <i class="fa-solid {{ $customer->user->status === 'blocked' ? 'fa-unlock' : 'fa-ban' }}"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No customers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $customers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
