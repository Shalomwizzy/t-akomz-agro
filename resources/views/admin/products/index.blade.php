@extends('layouts.admin')

@section('page-title', 'Products')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div></div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.products.export') }}?{{ request()->getQueryString() }}"
           class="btn-outline py-2 px-4 text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Export xlsx
        </a>
        <a href="{{ route('admin.products.create') }}" class="btn-primary py-2.5 px-5 text-sm">+ Add Product</a>
    </div>
</div>

{{-- Filters --}}
<div class="card p-4 mb-6">
    <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search products..." class="input py-2 text-sm flex-1 min-w-48">
        <select name="category" class="input py-2 text-sm w-auto">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="status" class="input py-2 text-sm w-auto">
            <option value="">All Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            <option value="low_stock" {{ request('status') === 'low_stock' ? 'selected' : '' }}>Low Stock</option>
        </select>
        <button type="submit" class="btn-primary py-2 px-4 text-sm">Filter</button>
        <a href="{{ route('admin.products.index') }}" class="btn-ghost py-2 px-4 text-sm">Clear</a>
    </form>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-header text-left">Product</th>
                    <th class="table-header text-left">Category</th>
                    <th class="table-header text-left">Price</th>
                    <th class="table-header text-left">Stock</th>
                    <th class="table-header text-left">Status</th>
                    <th class="table-header text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr class="hover:bg-surface-elevated/50 transition-colors">
                    <td class="table-cell">
                        <div class="flex items-center gap-3">
                            <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}"
                                 class="w-10 h-10 object-cover rounded-lg flex-shrink-0">
                            <div>
                                <p class="text-sm text-content-primary font-medium">{{ $product->name }}</p>
                                @if($product->sku)<p class="text-xs text-content-muted">{{ $product->sku }}</p>@endif
                                @if($product->is_featured)<span class="badge-green text-xs">Featured</span>@endif
                                @if($product->is_organic)<span class="badge-green text-xs ml-1">Organic</span>@endif
                            </div>
                        </div>
                    </td>
                    <td class="table-cell">
                        <span class="text-sm text-content-secondary">{{ $product->category->name ?? '—' }}</span>
                    </td>
                    <td class="table-cell">
                        <p class="text-sm font-semibold text-brand-green">{{ $product->formatted_price }}</p>
                        @if($product->formatted_compare_price)
                        <p class="text-xs text-content-muted line-through">{{ $product->formatted_compare_price }}</p>
                        @endif
                    </td>
                    <td class="table-cell">
                        <span class="text-sm font-semibold {{ $product->stock <= 0 ? 'text-red-400' : ($product->stock_status === 'low_stock' ? 'text-yellow-400' : 'text-brand-green') }}">
                            {{ $product->stock }}
                        </span>
                        <span class="text-xs text-content-muted"> / {{ $product->unit }}</span>
                    </td>
                    <td class="table-cell">
                        @if($product->is_active)
                        <span class="badge bg-brand-green/15 text-brand-green text-xs">Active</span>
                        @else
                        <span class="badge bg-red-500/15 text-red-400 text-xs">Inactive</span>
                        @endif
                    </td>
                    <td class="table-cell">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-xs text-brand-green hover:underline">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                  onsubmit="return confirm('Delete {{ $product->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:underline">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-content-muted text-sm">No products found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $products->withQueryString()->links() }}</div>
@endsection
