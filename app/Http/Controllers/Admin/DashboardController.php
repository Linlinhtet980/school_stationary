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

        // --- Chart Data: Sales Performance (Last 30 Days) ---
        $thirtyDaysAgo = Carbon::now()->subDays(29)->startOfDay(); // 30 days including today
        $salesDataRaw = Order::where('created_at', '>=', $thirtyDaysAgo)
            ->where('payment_status', 'paid')
            ->select(
                \DB::raw('DATE(created_at) as date'),
                \DB::raw('SUM(total_amount) as total_sales'),
                \DB::raw('COUNT(id) as total_orders')
            )
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $salesChartLabels = [];
        $salesChartData = [];
        $ordersChartData = [];

        for ($i = 29; $i >= 0; $i--) {
            $dateObj = Carbon::now()->subDays($i);
            $dateStr = $dateObj->format('Y-m-d');
            $salesChartLabels[] = $dateObj->format('M d');
            
            $dayData = $salesDataRaw->firstWhere('date', $dateStr);
            $salesChartData[] = $dayData ? (float)$dayData->total_sales : 0;
            $ordersChartData[] = $dayData ? (int)$dayData->total_orders : 0;
        }

        // --- Chart Data: Sales by Category (All time paid) ---
        $categorySales = \DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('item_variants', 'order_items.item_variant_id', '=', 'item_variants.id')
            ->join('items', 'item_variants.item_id', '=', 'items.id')
            ->join('types', 'items.type_id', '=', 'types.id')
            ->join('categories', 'types.category_id', '=', 'categories.id')
            ->where('orders.payment_status', 'paid')
            ->select('categories.name as category_name', \DB::raw('SUM(order_items.total_price) as total_sales'))
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_sales', 'DESC')
            ->get();

        $categoryChartLabels = $categorySales->pluck('category_name')->toArray();
        $categoryChartData = $categorySales->pluck('total_sales')->map(function($val) { return (float)$val; })->toArray();

        return view('admin.dashboard', compact(
            'dailySales', 
            'totalOrders', 
            'newCustomers', 
            'lowStockVariants', 
            'lowStockCount',
            'recentOrders',
            'salesChartLabels',
            'salesChartData',
            'ordersChartData',
            'categoryChartLabels',
            'categoryChartData'
        ));
    }

    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }
}
