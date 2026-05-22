<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderTrackingController extends Controller
{
    public function index(Request $request)
    {
        $order = null;
        $searched = $request->filled('order_number');

        if ($searched) {
            $request->validate([
                'order_number' => ['required', 'string'],
                'email'        => ['required', 'string'],
            ]);

            $order = Order::where('order_number', strtoupper(trim($request->order_number)))
                ->where(function ($q) use ($request) {
                    $q->where('customer_email', $request->email)
                      ->orWhere('customer_phone', $request->email);
                })
                ->with(['items', 'timeline' => fn($q) => $q->orderByDesc('created_at')])
                ->first();

            if (!$order) {
                return back()
                    ->withInput()
                    ->with('error', 'Order not found. Please check your order number and email / phone.');
            }
        }

        return view('orders.track', compact('order', 'searched'));
    }
}
