@extends('layouts.admin')

@section('title', 'Customer Details')
@section('header_title', 'Customer Details')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/customers.css') }}">
@endpush

@section('content')
<div class="profile-grid">
    <!-- Customer Profile -->
    <div class="card">
        <div class="card-header">
            <h2><i class="fa-solid fa-user"></i> Profile Info</h2>
        </div>
        <div class="card-body text-center">
            @if($customer->image)
                <img src="{{ Storage::url($customer->image) }}" alt="{{ $customer->name }}" class="profile-img-large">
            @else
                <div class="profile-img-large-placeholder">
                    <i class="fa-solid fa-user"></i>
                </div>
            @endif
            
            <h3 class="profile-title">{{ $customer->name }}</h3>
            <p class="profile-id">#CUST-{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}</p>

            <div class="profile-details-wrapper">
                <div class="profile-detail-row">
                    <span class="detail-label">Email</span>
                    <span class="detail-value">{{ $customer->user->email ?? 'N/A' }}</span>
                </div>
                <div class="profile-detail-row">
                    <span class="detail-label">Phone</span>
                    <span class="detail-value">{{ $customer->phone }}</span>
                </div>
                <div class="profile-detail-row">
                    <span class="detail-label">Gender</span>
                    <span class="detail-value capitalize">{{ $customer->gender ?? 'N/A' }}</span>
                </div>
                <div class="profile-detail-row">
                    <span class="detail-label">Date of Birth</span>
                    <span class="detail-value">{{ $customer->dob ? \Carbon\Carbon::parse($customer->dob)->format('d M Y') : 'N/A' }}</span>
                </div>
                <div class="profile-detail-row">
                    <span class="detail-label">Account Status</span>
                    <span>
                        @if($customer->user && $customer->user->status === 'blocked')
                            <span class="badge badge-blocked">Blocked</span>
                        @else
                            <span class="badge badge-active">Active</span>
                        @endif
                    </span>
                </div>
            </div>

            @if($customer->user)
                <div class="mt-4">
                    <form action="{{ route('admin.customers.block', $customer->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        <button type="submit" class="btn {{ $customer->user->status === 'blocked' ? 'btn-success' : 'btn-danger' }}">
                            <i class="fa-solid {{ $customer->user->status === 'blocked' ? 'fa-unlock' : 'fa-ban' }}"></i> 
                            {{ $customer->user->status === 'blocked' ? 'Unblock Customer' : 'Block Customer' }}
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <!-- Order History -->
    <div class="card">
        <div class="card-header">
            <h2><i class="fa-solid fa-clock-rotate-left"></i> Order History</h2>
        </div>
        <div class="card-body">
            <table class="data-table w-100">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customer->orders as $order)
                        <tr>
                            <td class="text-order-number">{{ $order->order_number }}</td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td class="text-amount">{{ number_format($order->total_amount, 2) }} Ks</td>
                            <td>
                                <span class="badge badge-status-{{ $order->status }}">{{ $order->status }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-icon btn-link-action"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">This customer hasn't placed any orders yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
