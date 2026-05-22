<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->orderBy('stock', 'asc');

        if ($request->filled('stock_status')) {
            match ($request->stock_status) {
                'out_of_stock' => $query->where('stock', 0),
                'low_stock'    => $query->whereRaw('stock > 0 AND stock <= low_stock_threshold'),
                'in_stock'     => $query->whereRaw('stock > low_stock_threshold'),
                default        => null,
            };
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(30)->withQueryString();

        $stats = [
            'total'       => Product::count(),
            'in_stock'    => Product::whereRaw('stock > low_stock_threshold')->count(),
            'low_stock'   => Product::whereRaw('stock > 0 AND stock <= low_stock_threshold')->count(),
            'out_of_stock' => Product::where('stock', 0)->count(),
        ];

        $categories = Category::active()->get();

        return view('admin.inventory.index', compact('products', 'stats', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'stock'             => ['required', 'integer', 'min:0'],
            'low_stock_threshold' => ['integer', 'min:0'],
        ]);

        $product->update($data);

        return back()->with('success', "Stock updated for {$product->name}.");
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate(['updates' => ['required', 'array']]);

        foreach ($request->updates as $id => $stock) {
            Product::where('id', $id)->update(['stock' => max(0, (int) $stock)]);
        }

        return back()->with('success', 'Inventory updated for ' . count($request->updates) . ' products.');
    }
}
