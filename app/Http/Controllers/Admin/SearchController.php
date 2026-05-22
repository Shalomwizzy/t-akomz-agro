<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 2) {
            return view('admin.search', ['q' => $q, 'products' => collect(), 'orders' => collect(), 'customers' => collect(), 'total' => 0]);
        }

        $like = '%' . $q . '%';

        $products = Product::where('name', 'like', $like)
            ->orWhere('sku', 'like', $like)
            ->orderBy('name')
            ->limit(8)
            ->get();

        $orders = Order::where('order_number', 'like', $like)
            ->orWhere('customer_name', 'like', $like)
            ->orWhere('customer_email', 'like', $like)
            ->orWhere('customer_phone', 'like', $like)
            ->latest()
            ->limit(8)
            ->get();

        $customers = User::role('CUSTOMER')
            ->where(function ($query) use ($like) {
                $query->where('name', 'like', $like)
                      ->orWhere('email', 'like', $like)
                      ->orWhere('phone', 'like', $like);
            })
            ->orderBy('name')
            ->limit(8)
            ->get();

        $total = $products->count() + $orders->count() + $customers->count();

        return view('admin.search', compact('q', 'products', 'orders', 'customers', 'total'));
    }
}
