@extends('layouts.admin')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/views/dashboard.css') }}">
@endpush


@section('title', 'Admin Dashboard')
@section('header_title', 'Welcome Back, ' . auth()->user()->name . '!')



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
            <span class="stat-pill inline-style-8" >All Time</span>
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
            <span class="stat-pill inline-style-9" >This Month</span>
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
                </div>
            </div>
            <div class="chart-area" style="position: relative; height:200px; width:100%;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
        
        <!-- Category Chart Mock -->
        <div class="card-container mb-0">
            <div class="card-header border-0">
                <h2 class="text-dark">Sales by Category</h2>
            </div>
            <div class="pie-chart-container" style="position: relative; height: 220px; width:100%;">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>
@endif

<div class="card-container mt-4">
    <div class="card-header">
        <h2><i class="fa-solid fa-clock"></i> Recent Orders</h2>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline btn-md">View All Orders</a>
    </div>
    <div class="table-responsive">
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
                        <td class="inline-style-10">{{ number_format($order->total_amount, 2) }} Ks</td>
                        <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>
                        <td>
                            <span class="badge inline-style-11" style="background-color: {{ $order->payment_status == 'paid' ? '#dcfce7' : ($order->payment_status == 'failed' ? '#fee2e2' : '#fef3c7') }}; color: {{ $order->payment_status == 'paid' ? '#16a34a' : ($order->payment_status == 'failed' ? '#dc2626' : '#d97706') }}; text-transform: capitalize;">{{ $order->payment_status }}</span>
                        </td>
                        <td>
                            <span class="badge inline-style-12" >{{ $order->status }}</span>
                        </td>
                        <td><a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm" style="background: #e0f2fe; color: #0284c7; text-decoration: none;">View</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="inline-style-13">No recent orders.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if(in_array(auth()->user()->role_id, [1, 2]) && $lowStockCount > 0)
<div class="card-container mt-4 inline-style-14" >
    <div class="card-header">
        <h2><i class="fa-solid fa-triangle-exclamation text-danger"></i> Low Stock Alerts (Stock &le; 5)</h2>
        <a href="{{ route('admin.items.index') }}" class="btn btn-outline btn-md">Manage Inventory</a>
    </div>
    <div class="table-responsive">
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
                        <td class="inline-style-15" style="color: #dc2626; font-weight: bold;">{{ $variant->stock_quantity }} Left</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartData = {
        salesLabels: @json($salesChartLabels ?? []),
        salesData: @json($salesChartData ?? []),
        ordersData: @json($ordersChartData ?? []),
        categoryLabels: @json($categoryChartLabels ?? []),
        categoryData: @json($categoryChartData ?? [])
    };
</script>
<script src="{{ asset('js/admin/dashboard-charts.js') }}?v={{ time() }}"></script>
@endpush
