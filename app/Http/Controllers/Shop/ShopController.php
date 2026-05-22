<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with('images', 'category');

        // Filters
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('short_description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->boolean('in_stock')) {
            $query->where('stock', '>', 0);
        }

        if ($request->boolean('organic')) {
            $query->where('is_organic', true);
        }

        // Sorting
        match ($request->sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'popular'    => $query->withCount('orderItems')->orderByDesc('order_items_count'),
            'oldest'     => $query->orderBy('created_at', 'asc'),
            default      => $query->orderByDesc('created_at'),
        };

        $products   = $query->paginate(24)->withQueryString();
        $categories = Category::active()->withCount('activeProducts')->get();

        $seoTitle       = 'Shop Farm-Fresh Products';
        $seoDescription = 'Browse premium poultry, eggs, livestock, and fresh farm produce from T-Akomz Agro Estates. Order online with fast delivery across Nigeria.';

        return view('shop.index', compact('products', 'categories', 'seoTitle', 'seoDescription'));
    }

    public function category(Category $category)
    {
        abort_unless($category->is_active, 404);

        $products = Product::active()
            ->where('category_id', $category->id)
            ->with('images')
            ->orderByDesc('is_featured')
            ->orderByDesc('created_at')
            ->paginate(24);

        $categories = Category::active()->withCount('activeProducts')->get();

        $seoTitle       = $category->name . ' — Farm Fresh';
        $seoDescription = $category->description ?? 'Shop fresh ' . strtolower($category->name) . ' from T-Akomz Agro Estates. Quality guaranteed, fast delivery.';
        $seoImage       = $category->image ? asset('storage/' . $category->image) : null;

        return view('shop.category', compact('category', 'products', 'categories', 'seoTitle', 'seoDescription', 'seoImage'));
    }

    public function product(Category $category, Product $product)
    {
        abort_unless($product->is_active && $product->category_id === $category->id, 404);

        $product->load([
            'images',
            'category',
            'reviews' => fn($q) => $q->where('is_approved', true)->with('user')->latest(),
        ]);

        $related = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('images')
            ->inStock()
            ->limit(4)
            ->get();

        $wishlistIds = auth()->check()
            ? auth()->user()->wishlistItems()->pluck('product_id')->toArray()
            : [];

        $hasReviewed = auth()->check()
            ? \App\Models\Review::where('user_id', auth()->id())->where('product_id', $product->id)->exists()
            : false;

        $seoTitle       = $product->name . ' — ' . $category->name;
        $seoDescription = $product->short_description ?? strip_tags(substr($product->description ?? '', 0, 160));
        $seoImage       = $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : null;
        $seoType        = 'product';

        return view('shop.product', compact('product', 'category', 'related', 'wishlistIds', 'hasReviewed', 'seoTitle', 'seoDescription', 'seoImage', 'seoType'));
    }
}
