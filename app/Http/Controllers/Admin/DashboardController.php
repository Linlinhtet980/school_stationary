<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use App\Models\ItemVariant;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Daily Sales Metrics (Today's total paid orders)
        $dailySales = Order::whereDate('created_at', Carbon::today())
                           ->where('payment_status', 'paid')
                           ->sum('total_amount');

        // Total Order Volumes (Overall)
        $totalOrders = Order::count();

        // New Registered Customer Counts (This month)
        $newCustomers = Customer::whereMonth('created_at', Carbon::now()->month)
                                ->whereYear('created_at', Carbon::now()->year)
                                ->count();

        // Low Stock Alerts (Items where stock is <= 5)
        $lowStockVariants = ItemVariant::with('item')
                                       ->where('stock_quantity', '<=', 5)
                                       ->get();
        
        $lowStockCount = $lowStockVariants->count();

        // Recent Orders for the dashboard table
        $recentOrders = Order::with('user.customer')
                             ->latest()
                             ->take(5)
                             ->get();

        return view('admin.dashboard', compact(
            'dailySales', 
            'totalOrders', 
            'newCustomers', 
            'lowStockVariants', 
            'lowStockCount',
            'recentOrders'
        ));
    }

    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }
}
