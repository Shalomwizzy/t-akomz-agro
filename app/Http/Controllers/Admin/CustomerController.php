<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount('orders')
                     ->withSum('orders', 'total')
                     ->where(function ($q) {
                         $q->whereHas('roles', fn($r) => $r->where('name', 'CUSTOMER'))
                           ->orWhereDoesntHave('roles');
                     })
                     ->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $customers = $query->paginate(25)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $user)
    {
        $customer = $user;
        $customer->load('orders.items.product', 'addresses', 'wishlistItems');
        $orders = $customer->orders()->latest()->get();
        return view('admin.customers.show', compact('customer', 'orders'));
    }
}
