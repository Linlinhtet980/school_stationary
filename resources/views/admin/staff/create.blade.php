@extends('layouts.admin')

@section('title', 'Add New Staff')
@section('header_title', 'Create Staff Account')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/staff_form.css') }}">
@endpush

@section('content')
<div class="form-card">
    <div class="card-header">
        <h2><i class="fa-solid fa-user-plus"></i> Register New Staff</h2>
    </div>

    <div class="card-body">
        @if($errors->any())
            <div class="alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.staff.store') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label for="name">Full Name <span class="text-required">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address (Login ID) <span class="text-required">*</span></label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label for="role_id">Assigned Role <span class="text-required">*</span></label>
                    <select id="role_id" name="role_id" class="form-control" required>
                        <option value="">-- Select Role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>

                <div class="form-group">
                    <label for="password">Password <span class="text-required">*</span></label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" class="form-control" required minlength="8">
                        <i class="fa-solid fa-eye password-toggle" id="togglePassword" onclick="togglePasswordVisibility('password', 'togglePassword')"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="status">Account Status</label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active (Can Login)</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive (Suspended)</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.staff.index') }}" class="btn-outline">Cancel</a>
                <button type="submit" class="btn"><i class="fa-solid fa-save"></i> Create Account</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin/staff.js') }}"></script>
@endpush