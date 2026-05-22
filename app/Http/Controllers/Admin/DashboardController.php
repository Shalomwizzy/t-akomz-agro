<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $now       = now();
        $thisMonth = $now->copy()->startOfMonth();
        $lastMonth = $now->copy()->subMonth()->startOfMonth();

        // KPI stats
        $monthRevenue   = Order::where('payment_status', 'PAID')->whereMonth('created_at', $now->month)->sum('total');
        $lastRevenue    = Order::where('payment_status', 'PAID')->whereBetween('created_at', [$lastMonth, $thisMonth])->sum('total');
        $revenueChange  = $lastRevenue > 0 ? round((($monthRevenue - $lastRevenue) / $lastRevenue) * 100, 1) : 0;
        $monthOrders    = Order::whereMonth('created_at', $now->month)->count();
        $allOrders      = Order::count();
        $newCustomers   = User::whereMonth('created_at', $now->month)->count();
        $activeProducts = Product::where('is_active', true)->count();
        $lowStock       = Product::whereRaw('stock <= low_stock_threshold')->where('is_active', true)->count();

        $stats = [
            'monthly_revenue' => $monthRevenue,
            'revenue_change'  => $revenueChange,
            'monthly_orders'  => $monthOrders,
            'total_orders'    => $allOrders,
            'new_customers'   => $newCustomers,
            'active_products' => $activeProducts,
            'low_stock_count' => $lowStock,
        ];

        // Recent orders
        $recentOrders = Order::with('items')->latest()->limit(10)->get();

        // Revenue chart (last 12 months) — collection of ['month' => ..., 'revenue' => ...]
        $monthExpr = DB::connection()->getDriverName() === 'sqlite'
            ? "strftime('%Y-%m', created_at)"
            : "DATE_FORMAT(created_at, '%Y-%m')";

        $revenueChart = Order::where('payment_status', 'PAID')
            ->where('created_at', '>=', now()->subMonths(12))
            ->select(DB::raw("{$monthExpr} as month"), DB::raw('sum(total) as revenue'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top products
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('sum(order_items.total_price) as revenue'), DB::raw('sum(order_items.quantity) as total_qty'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        // Order status breakdown — collection of ['status' => ..., 'count' => ...]
        $orderStatusBreakdown = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Low stock products
        $lowStockProducts = Product::whereRaw('stock <= low_stock_threshold')
            ->where('is_active', true)
            ->orderBy('stock')
            ->limit(10)
            ->get();

        // Latest customers
        $latestCustomers = User::whereHas('roles', fn($q) => $q->where('name', 'CUSTOMER'))
            ->latest()->limit(5)->get();

        return view('admin.dashboard', compact(
            'stats', 'recentOrders', 'revenueChart', 'topProducts',
            'orderStatusBreakdown', 'lowStockProducts', 'latestCustomers'
        ));
    }
}
