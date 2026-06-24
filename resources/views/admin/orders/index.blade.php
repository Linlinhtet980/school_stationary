@extends('layouts.admin')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/views/orders.css') }}">
@endpush


@section('title', 'Orders')
@section('header_title', 'Orders Management')



@section('content')
<div class="card">
    <div class="card-header">
        <h2><i class="fa-solid fa-cart-shopping"></i> Customer Orders</h2>
        <div class="header-actions">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="search-form live-search-form">
                <i class="fa-solid fa-search search-icon"></i>
                <input type="text" name="search" placeholder="Search orders..." value="{{ request('search') }}">
                
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
                                <input type="radio" name="status" value="pending" onchange="applyFilters()" {{ request('status') == 'pending' ? 'checked' : '' }}> Pending
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="status" value="processing" onchange="applyFilters()" {{ request('status') == 'processing' ? 'checked' : '' }}> Processing
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="status" value="completed" onchange="applyFilters()" {{ request('status') == 'completed' ? 'checked' : '' }}> Completed
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="status" value="cancelled" onchange="applyFilters()" {{ request('status') == 'cancelled' ? 'checked' : '' }}> Cancelled
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
        
        <div id="tableDataContainer">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total Amount</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td class="id-column">{{ $order->order_number }}</td>
                                <td>
                                    <div class="customer-name">{{ $order->customer->name ?? 'Unknown' }}</div>
                                    <div class="customer-phone">{{ $order->customer->phone ?? 'N/A' }}</div>
                                </td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                                <td class="amount-column">{{ number_format($order->total_amount) }} MMK</td>
                                <td>
                                    @if($order->payment_status === 'paid')
                                        <span class="payment-badge payment-paid">Paid</span>
                                    @else
                                        <span class="payment-badge payment-pending">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusClass = match($order->status) {
                                            'completed' => 'status-completed',
                                            'processing' => 'status-processing',
                                            'shipped' => 'status-shipped',
                                            'cancelled' => 'status-cancelled',
                                            default => 'status-pending'
                                        };
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-icon btn-view" title="View Order">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-icon btn-edit" title="Update Status">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-state">
                                    <div class="empty-state-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                                    <h3>No Orders Found</h3>
                                    <p>There are no orders matching your criteria.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders instanceof \Illuminate\Pagination\LengthAwarePaginator && $orders->hasPages())
                <div class="pagination-container">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
