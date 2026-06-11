@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('header_title', 'Welcome Back, ' . auth()->user()->name . '!')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
@endpush

@section('content')
<h2>Store Dashboard Overview</h2>

<div class="grid-4">
    @if(in_array(auth()->user()->role_id, [1, 5]))
        <div class="stat-card">
            <div class="stat-header">
                <h3 class="stat-title">Today's Sales</h3>
                <span class="stat-pill">+{{ number_format($dailySales, 0) > 0 ? 'Active' : 'No Sales Yet' }}</span>
            </div>
            <div class="stat-value">{{ number_format($dailySales, 0) }} Ks</div>
            <div class="stat-footer"><i class="fa-solid fa-money-bill-trend-up"></i> Last 24 Hours (Paid)</div>
        </div>
    @endif

    <div class="stat-card">
        <div class="stat-header">
            <h3 class="stat-title text-primary">Total Orders</h3>
            <span class="stat-pill" style="background: #EBF4FF; color: #3182CE;">All Time</span>
        </div>
        <div class="stat-value text-primary">{{ number_format($totalOrders) }}</div>
        <div class="stat-footer"><i class="fa-solid fa-receipt"></i> Lifetime Orders</div>
    </div>

    @if(in_array(auth()->user()->role_id, [1, 2]))
        <div class="stat-card alert-card">
            <div class="stat-header">
                <h3 class="stat-title text-danger">Low Stock Items</h3>
                <span class="stat-pill bg-danger text-danger">Warning</span>
            </div>
            <div class="stat-value text-danger">{{ number_format($lowStockCount) }}</div>
            <div class="stat-footer"><i class="fa-solid fa-boxes-stacked"></i> Under 5 units left</div>
        </div>
    @endif

    <div class="stat-card">
        <div class="stat-header">
            <h3 class="stat-title">New Customers</h3>
            <span class="stat-pill" style="background: #EBF4FF; color: #3182CE;">This Month</span>
        </div>
        <div class="stat-value">{{ number_format($newCustomers) }}</div>
        <div class="stat-footer"><i class="fa-solid fa-users"></i> Registered Accounts</div>
    </div>
</div>

@if(in_array(auth()->user()->role_id, [1, 5]))
    <!-- Financial Dashboard Area (Restricted) -->
    <div class="grid-2-asymmetric">
        <!-- Sales Chart Mock -->
        <div class="card-container mb-0">
            <div class="card-header border-0 mb-0">
                <div>
                    <h2 class="text-dark">Sales Performance (Last 30 Days)</h2>
                    <div class="chart-legend"><div class="chart-dot"></div> Blue line</div>
                </div>
                <div class="text-right">
                    <div class="flex-gap-20">
                        <div>
                            <div class="stat-label">Sales</div>
                            <div class="stat-number">Mock Data</div>
                        </div>
                        <div>
                            <div class="stat-label">Orders</div>
                            <div class="stat-number">Mock Data</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="chart-area">
                <svg viewBox="0 0 500 150" preserveAspectRatio="none" class="chart-svg">
                    <defs>
                        <linearGradient id="chartGradient" x1="0" x2="0" y1="0" y2="1">
                            <stop offset="0%" stop-color="rgba(49, 130, 206, 0.2)"></stop>
                            <stop offset="100%" stop-color="rgba(49, 130, 206, 0)"></stop>
                        </linearGradient>
                    </defs>
                    <path d="M0,100 Q25,20 50,80 T100,50 T150,120 T200,20 T250,100 T300,50 T350,110 T400,60 T450,120 T500,40 L500,150 L0,150 Z" fill="url(#chartGradient)"></path>
                    <path d="M0,100 Q25,20 50,80 T100,50 T150,120 T200,20 T250,100 T300,50 T350,110 T400,60 T450,120 T500,40" fill="none" stroke="#3182CE" stroke-width="3"></path>
                </svg>
            </div>
        </div>
        
        <!-- Category Chart Mock -->
        <div class="card-container mb-0">
            <div class="card-header border-0">
                <h2 class="text-dark">Sales by Category</h2>
            </div>
            <div class="pie-chart-container">
                <div class="pie-chart"></div>
                <div class="pie-label pie-label-1">Notebooks<br>35%</div>
                <div class="pie-label pie-label-2">Pens<br>25%</div>
                <div class="pie-label pie-label-3">Art Supplies<br>20%</div>
            </div>
        </div>
    </div>
@endif

<div class="card-container mt-4">
    <div class="card-header">
        <h2><i class="fa-solid fa-clock"></i> Recent Orders</h2>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-md">View All Orders</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Total Amount</th>
                <th>Date/Time</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentOrders as $order)
                <tr>
                    <td><a href="{{ route('admin.orders.show', $order->id) }}" class="table-link" style="color: #0284c7; text-decoration: none; font-weight: 600;">{{ $order->order_number }}</a></td>
                    <td>{{ $order->customer->name ?? 'Unknown' }}</td>
                    <td style="font-weight: 600;">{{ number_format($order->total_amount, 2) }} Ks</td>
                    <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                    <td>
                        <span class="badge" style="background: {{ $order->payment_status == 'paid' ? '#dcfce7' : ($order->payment_status == 'failed' ? '#fee2e2' : '#fef3c7') }}; color: {{ $order->payment_status == 'paid' ? '#16a34a' : ($order->payment_status == 'failed' ? '#dc2626' : '#d97706') }}; text-transform: capitalize;">{{ $order->payment_status }}</span>
                    </td>
                    <td>
                        <span class="badge" style="background: #f1f5f9; color: #475569; text-transform: capitalize;">{{ $order->status }}</span>
                    </td>
                    <td><a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm" style="background: #e0f2fe; color: #0284c7; text-decoration: none;">View</a></td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #64748b; padding: 20px;">No recent orders.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(in_array(auth()->user()->role_id, [1, 2]) && $lowStockCount > 0)
<div class="card-container mt-4" style="border-left: 4px solid #ef4444;">
    <div class="card-header">
        <h2><i class="fa-solid fa-triangle-exclamation text-danger"></i> Low Stock Alerts (Stock &le; 5)</h2>
        <a href="{{ route('admin.items.index') }}" class="btn btn-outline btn-md">Manage Inventory</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Variant Label</th>
                <th>SKU</th>
                <th>Current Stock</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lowStockVariants as $variant)
                <tr>
                    <td>{{ $variant->item->name ?? 'N/A' }}</td>
                    <td>{{ $variant->unit_label }} {{ $variant->color ? '('.$variant->color.')' : '' }}</td>
                    <td>{{ $variant->sku ?: 'No SKU' }}</td>
                    <td style="font-weight: bold; color: #ef4444;">{{ $variant->stock_quantity }} Left</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@endsection
