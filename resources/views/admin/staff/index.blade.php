@extends('layouts.admin')

@section('title', 'Staff Management')
@section('header_title', 'Staff Accounts')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/staff_index.css') }}">
@endpush

@section('content')
<div class="staff-card">
    <div class="card-header">
        <h2><i class="fa-solid fa-users-gear"></i> Store Administrators & Staff</h2>
        <a href="{{ route('admin.staff.create') }}" class="btn"><i class="fa-solid fa-user-plus"></i> Add New Staff</a>
    </div>

    @if(session('success'))
        <div class="alert-success"><i class="fa-solid fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert-error"><i class="fa-solid fa-triangle-exclamation"></i> {{ $errors->first() }}</div>
    @endif

    <div class="table-responsive">
        <table class="staff-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($staffUsers as $user)
                <tr>
                    <td><strong>{{ $user->staff->name ?? 'N/A' }}</strong></td>
                    <td>{{ $user->email }}</td>
                    <td><span class="role-badge">{{ $user->role->name ?? 'Unknown' }}</span></td>
                    <td>{{ $user->staff->phone ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $user->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('admin.staff.edit', $user->id) }}" class="btn-icon btn-edit" title="Edit Staff"><i class="fa-solid fa-pen"></i></a>
                            @if(auth()->id() !== $user->id)
                            <form action="{{ route('admin.staff.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this staff account?');" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon btn-delete" title="Remove"><i class="fa-solid fa-user-xmark"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection