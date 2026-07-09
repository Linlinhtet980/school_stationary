@extends('layouts.admin')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/views/staff_index.css') }}">
@endpush


@section('title', 'Staff Management')
@section('header_title', 'Staff Accounts')



@section('content')
<div class="staff-card">
    <div class="card-header">
        <h2><i class="fa-solid fa-users-gear"></i> Store Administrators & Staff</h2>
        <div class="header-actions">
            <form action="{{ route('admin.staff.index') }}" method="GET" class="search-form live-search-form">
                <i class="fa-solid fa-search search-icon"></i>
                <input type="text" name="search" placeholder="Search staff..." value="{{ request('search') }}">
                
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
            <a href="{{ route('admin.staff.create') }}" class="btn-primary"><i class="fa-solid fa-user-plus"></i> Add New Staff</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success"><i class="fa-solid fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert-error"><i class="fa-solid fa-triangle-exclamation"></i> {{ $errors->first() }}</div>
    @endif

    <div id="tableDataContainer">
        <div class="table-responsive">
            <table class="staff-table">
                <thead>
                    <tr>
                        <th>Staff ID</th>
                        <th>Profile</th>
                        <th>Contact Info</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($staffUsers as $user)
                        <tr>
                            <td class="id-column">EMP-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <div class="staff-profile">
                                    <div class="staff-avatar">
                                        <i class="fa-solid fa-user-tie"></i>
                                    </div>
                                    <div class="staff-name">{{ $user->staff->name ?? 'N/A' }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="contact-info">
                                    <span><i class="fa-solid fa-envelope"></i> {{ $user->email }}</span>
                                    <span><i class="fa-solid fa-phone"></i> {{ $user->staff->phone ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="role-badge role-{{ strtolower($user->role->name ?? 'unknown') }}">
                                    {{ ucfirst($user->role->name ?? 'Unknown') }}
                                </span>
                            </td>
                            <td>
                                @if($user->status === 'active')
                                    <span class="status-badge status-active"><i class="fa-solid fa-circle-check"></i> Active</span>
                                @else
                                    <span class="status-badge status-inactive"><i class="fa-solid fa-circle-xmark"></i> Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.staff.edit', $user->id) }}" class="btn-icon btn-edit" title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    @if(auth()->id() !== $user->id)
                                        <form action="{{ route('admin.staff.destroy', $user->id) }}" method="POST" onsubmit="event.preventDefault(); showConfirmModal('Are you sure you want to delete this staff member?', () => this.submit());" class="form-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-icon btn-delete" title="Delete">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <div class="empty-state-icon"><i class="fa-solid fa-users-slash"></i></div>
                                <h3>No Staff Found</h3>
                                <p>There are no staff members matching your criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($staffUsers instanceof \Illuminate\Pagination\LengthAwarePaginator && $staffUsers->hasPages())
            <div class="pagination-container">
                {{ $staffUsers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection