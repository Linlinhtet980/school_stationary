@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('header_title', 'Welcome Back, Admin!')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
@endpush

@section('content')
<h2>Store Dashboard Overview</h2>

<div class="grid-4">
    <div class="stat-card">
        <div class="stat-header">
            <h3 class="stat-title">Today's Sales</h3>
            <span class="stat-pill">+5.2%</span>
        </div>
        <div class="stat-value">1,450,000 Ks</div>
        <div class="stat-footer"><i class="fa-solid fa-money-bill-trend-up"></i> Last 24 Hours</div>
    </div>
    <div class="stat-card alert-card">
        <div class="stat-header">
            <h3 class="stat-title text-danger">Pending Orders</h3>
            <span class="stat-pill bg-danger text-danger">Action Needed</span>
        </div>
        <div class="stat-value text-danger">15</div>
        <div class="stat-footer"><i class="fa-solid fa-clock"></i> Awaiting payment check</div>
    </div>
    <div class="stat-card alert-card">
        <div class="stat-header">
            <h3 class="stat-title text-danger">Low Stock Items</h3>
            <span class="stat-pill bg-danger text-danger">Warning</span>
        </div>
        <div class="stat-value text-danger">8</div>
        <div class="stat-footer"><i class="fa-solid fa-boxes-stacked"></i> Under 10 units left</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <h3 class="stat-title">Total Customers</h3>
            <span class="stat-pill" style="background: #EBF4FF; color: #3182CE;">+120 This Week</span>
        </div>
        <div class="stat-value">4,520</div>
        <div class="stat-footer"><i class="fa-solid fa-users"></i> Registered Accounts</div>
    </div>
</div>

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
                        <div class="stat-number">45.3K Ks</div>
                    </div>
                    <div>
                        <div class="stat-label">Orders</div>
                        <div class="stat-number">1.2K</div>
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

<div class="card-container mt-4">
    <div class="card-header">
        <h2><i class="fa-solid fa-circle-exclamation text-danger"></i> Recent Orders Needing Attention</h2>
        <button class="btn btn-outline btn-md">View All Orders</button>
    </div>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Total Amount</th>
                <th>Date/Time</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><a href="#" class="table-link">#ORD-10023</a></td>
                <td>Aung Aung</td>
                <td>45,000 Ks</td>
                <td>Today, 10:30 AM</td>
                <td><span class="badge badge-warning-light">Pending Payment</span></td>
                <td><button class="btn btn-sm">Check Slip</button></td>
            </tr>
            <tr>
                <td><a href="#" class="table-link">#ORD-10022</a></td>
                <td>Su Myat</td>
                <td>12,500 Ks</td>
                <td>Today, 09:15 AM</td>
                <td><span class="badge badge-warning-light">Pending Payment</span></td>
                <td><button class="btn btn-sm">Check Slip</button></td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
