@extends('layouts.admin')

@section('page-title', 'Inventory')

@section('content')

{{-- Summary Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach([
        ['Total Products', $stats['total'], 'text-content-primary'],
        ['In Stock', $stats['in_stock'], 'text-brand-green'],
        ['Low Stock', $stats['low_stock'], 'text-yellow-400'],
        ['Out of Stock', $stats['out_of_stock'], 'text-red-400'],
    ] as [$label, $count, $color])
    <div class="card p-4 text-center">
        <p class="font-display text-2xl font-bold {{ $color }}">{{ $count }}</p>
        <p class="text-xs text-content-muted mt-1">{{ $label }}</p>
    </div>
    @endforeach
</div>

{{-- Filters --}}
<div class="card p-4 mb-6">
    <form action="{{ route('admin.inventory.index') }}" method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="input py-2 text-sm flex-1 min-w-48">
        <select name="stock_status" class="input py-2 text-sm w-auto">
            <option value="">All Stock</option>
            <option value="in_stock" {{ request('stock_status') === 'in_stock' ? 'selected' : '' }}>In Stock</option>
            <option value="low_stock" {{ request('stock_status') === 'low_stock' ? 'selected' : '' }}>Low Stock</option>
            <option value="out_of_stock" {{ request('stock_status') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
        </select>
        <select name="category" class="input py-2 text-sm w-auto">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-primary py-2 px-4 text-sm">Filter</button>
        <a href="{{ route('admin.inventory.index') }}" class="btn-ghost py-2 px-4 text-sm">Clear</a>
    </form>
</div>

{{-- Table --}}
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-header text-left">Product</th>
                    <th class="table-header text-left">Category</th>
                    <th class="table-header text-left">Stock</th>
                    <th class="table-header text-left">Threshold</th>
                    <th class="table-header text-left">Status</th>
                    <th class="table-header text-left">Quick Update</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                @php
                    $status = $product->stock_status;
                    $statusClass = $status === 'out_of_stock' ? 'text-red-400' : ($status === 'low_stock' ? 'text-yellow-400' : 'text-brand-green');
                @endphp
                <tr class="hover:bg-surface-elevated/50 transition-colors">
                    <td class="table-cell">
                        <div class="flex items-center gap-3">
                            <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}"
                                 class="w-8 h-8 object-cover rounded-lg flex-shrink-0">
                            <span class="text-sm text-content-primary font-medium line-clamp-1">{{ $product->name }}</span>
                        </div>
                    </td>
                    <td class="table-cell"><span class="text-xs text-content-muted">{{ $product->category->name }}</span></td>
                    <td class="table-cell">
                        <span class="font-bold text-sm {{ $statusClass }}">{{ $product->stock }}</span>
                        <span class="text-xs text-content-muted"> {{ $product->unit }}</span>
                    </td>
                    <td class="table-cell"><span class="text-xs text-content-muted">{{ $product->low_stock_threshold }}</span></td>
                    <td class="table-cell">
                        @if($status === 'out_of_stock')
                        <span class="badge bg-red-500/15 text-red-400 text-xs">Out of Stock</span>
                        @elseif($status === 'low_stock')
                        <span class="badge bg-yellow-500/15 text-yellow-400 text-xs">Low Stock</span>
                        @else
                        <span class="badge bg-brand-green/15 text-brand-green text-xs">In Stock</span>
                        @endif
                    </td>
                    <td class="table-cell">
                        <form action="{{ route('admin.inventory.update', $product) }}" method="POST" class="flex gap-2">
                            @csrf
                            <input type="number" name="stock" value="{{ $product->stock }}"
                                   class="input py-1.5 px-3 text-sm w-20" min="0">
                            <button type="submit" class="btn-primary py-1.5 px-3 text-xs">Update</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-12 text-center text-content-muted text-sm">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $products->withQueryString()->links() }}</div>
@endsection
