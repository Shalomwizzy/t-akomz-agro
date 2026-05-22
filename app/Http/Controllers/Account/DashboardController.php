<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total_orders'     => $user->orders()->count(),
            'delivered_orders' => $user->orders()->where('status', 'DELIVERED')->count(),
            'wishlist_count'   => $user->wishlistItems()->count(),
            'address_count'    => $user->addresses()->count(),
        ];

        $recentOrders = $user->orders()->with(['items.product'])->latest()->limit(5)->get();

        return view('account.dashboard', compact('stats', 'recentOrders'));
    }
}
