<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class DeliveryController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = Order::whereIn('status', ['CONFIRMED', 'PROCESSING', 'PACKED', 'DISPATCHED', 'OUT_FOR_DELIVERY'])
                      ->with('items')
                      ->latest();

        if ($request->filled('delivery_type')) {
            $query->where('delivery_type', $request->delivery_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(25)->withQueryString();

        $stats = [
            'pending'          => Order::where('status', 'CONFIRMED')->count(),
            'out_for_delivery' => Order::where('status', 'OUT_FOR_DELIVERY')->count(),
            'delivered_today'  => Order::where('status', 'DELIVERED')->whereDate('updated_at', today())->count(),
            'active'           => Order::whereIn('status', ['CONFIRMED', 'PROCESSING', 'PACKED', 'DISPATCHED', 'OUT_FOR_DELIVERY'])->count(),
        ];

        return view('admin.deliveries.index', compact('orders', 'stats'));
    }
}
