<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $days = (int) $request->get('days', 30);
        $from = now()->subDays($days)->startOfDay();

        $revenue     = Order::where('payment_status', 'PAID')->where('created_at', '>=', $from)->sum('total');
        $ordersCount = Order::where('created_at', '>=', $from)->count();
        $avgOrder    = $ordersCount > 0 ? round($revenue / $ordersCount, 2) : 0;
        $newCustomers = User::where('created_at', '>=', $from)->count();

        // Daily revenue chart
        $dailyRevenue = Order::where('payment_status', 'PAID')
            ->where('created_at', '>=', $from)
            ->select(DB::raw("date(created_at) as date"), DB::raw('sum(total) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        // Top products by revenue
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.created_at', '>=', $from)
            ->select('products.name', DB::raw('sum(order_items.total_price) as revenue'), DB::raw('sum(order_items.quantity) as units'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        // Sales by category
        $byCategory = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.created_at', '>=', $from)
            ->select('categories.name', DB::raw('sum(order_items.total_price) as revenue'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('revenue')
            ->get();

        // Payment method breakdown
        $byPayment = Order::where('created_at', '>=', $from)
            ->whereNotNull('payment_method')
            ->select('payment_method', DB::raw('count(*) as count'))
            ->groupBy('payment_method')
            ->pluck('count', 'payment_method');

        // Delivery type breakdown
        $byDelivery = Order::where('created_at', '>=', $from)
            ->select('delivery_type', DB::raw('count(*) as count'))
            ->groupBy('delivery_type')
            ->pluck('count', 'delivery_type');

        return view('admin.analytics.index', compact(
            'revenue', 'ordersCount', 'avgOrder', 'newCustomers',
            'dailyRevenue', 'topProducts', 'byCategory', 'byPayment', 'byDelivery', 'days'
        ));
    }
}
