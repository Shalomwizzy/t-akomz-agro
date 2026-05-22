@extends('layouts.app')

@section('title', $category->meta_title ?? $category->name . ' - T-Akomz Agro Estates')
@section('meta_description', $category->meta_description ?? 'Shop fresh ' . $category->name . ' from T-Akomz Agro Estates. Farm-to-door delivery across Nigeria.')

@section('content')

{{-- Category Hero --}}
<div class="relative bg-surface-card border-b border-surface-border overflow-hidden">
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: repeating-linear-gradient(45deg, #B8F397 0, #B8F397 1px, transparent 0, transparent 50%); background-size: 20px 20px;"></div>
    </div>
    <div class="container-custom py-12 relative">
        <nav class="flex items-center gap-2 text-sm text-content-muted mb-4">
            <a href="{{ route('home') }}" class="hover:text-brand-green transition-colors">Home</a>
            <span>/</span>
            <a href="{{ route('shop.index') }}" class="hover:text-brand-green transition-colors">Shop</a>
            <span>/</span>
            <span class="text-content-primary">{{ $category->name }}</span>
        </nav>
        <div class="flex items-center gap-4">
            @if($category->icon)
            <span class="text-5xl">{{ $category->icon }}</span>
            @endif
            <div>
                <h1 class="font-display text-3xl md:text-4xl font-bold text-content-primary">{{ $category->name }}</h1>
                @if($category->description)
                <p class="text-content-secondary mt-2 max-w-xl">{{ $category->description }}</p>
                @endif
                <p class="text-content-muted text-sm mt-1">{{ $products->total() }} {{ Str::plural('product', $products->total()) }} available</p>
            </div>
        </div>
    </div>
</div>

<div class="container-custom py-8">
    <div class="flex flex-col lg:flex-row gap-8">

        {{-- ─── SIDEBAR ──────────────────────────────────────────────────────── --}}
        <aside class="lg:w-64 flex-shrink-0" x-data="{ open: false }">
            <button @click="open = !open" class="lg:hidden w-full flex items-center justify-between card py-3 px-4 mb-4">
                <span class="font-semibold text-sm">Filters</span>
                <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>

            <div class="hidden lg:block space-y-6" :class="open ? '!block' : ''">
                <form action="{{ route('shop.category', $category->slug) }}" method="GET" id="filter-form">

                    {{-- Search --}}
                    <div class="card p-4">
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Search in {{ $category->name }}..."
                                   class="input pr-10 text-sm">
                            <button type="submit" class="absolute right-3 top-3 text-content-muted hover:text-brand-green">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- All Categories --}}
                    <div class="card p-4">
                        <h3 class="font-semibold text-sm text-content-primary mb-3">All Categories</h3>
                        <div class="space-y-2">
                            <a href="{{ route('shop.index') }}" class="flex items-center justify-between text-sm text-content-secondary hover:text-brand-green transition-colors py-1">
                                <span>All Products</span>
                            </a>
                            @foreach($categories as $cat)
                            <a href="{{ route('shop.category', $cat->slug) }}"
                               class="flex items-center justify-between text-sm transition-colors py-1 {{ $cat->id === $category->id ? 'text-brand-green font-semibold' : 'text-content-secondary hover:text-brand-green' }}">
                                <span>{{ $cat->name }}</span>
                                <span class="text-xs text-content-muted">{{ $cat->active_products_count }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Price Range --}}
                    <div class="card p-4">
                        <h3 class="font-semibold text-sm text-content-primary mb-3">Price Range</h3>
                        <div class="flex gap-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}"
                                   placeholder="Min ₦" class="input text-xs py-2 px-3">
                            <input type="number" name="max_price" value="{{ request('max_price') }}"
                                   placeholder="Max ₦" class="input text-xs py-2 px-3">
                        </div>
                    </div>

                    {{-- Toggles --}}
                    <div class="card p-4 space-y-3">
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-sm text-content-secondary">In Stock Only</span>
                            <input type="checkbox" name="in_stock" value="1" class="w-4 h-4 accent-green-500"
                                   {{ request('in_stock') ? 'checked' : '' }} onchange="this.form.submit()">
                        </label>
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-sm text-content-secondary">Organic Only</span>
                            <input type="checkbox" name="organic" value="1" class="w-4 h-4 accent-green-500"
                                   {{ request('organic') ? 'checked' : '' }} onchange="this.form.submit()">
                        </label>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="btn-primary flex-1 py-2.5 text-sm">Apply</button>
                        <a href="{{ route('shop.category', $category->slug) }}" class="btn-ghost flex-1 py-2.5 text-sm text-center">Clear</a>
                    </div>
                </form>
            </div>
        </aside>

        {{-- ─── PRODUCT GRID ────────────────────────────────────────────────── --}}
        <div class="flex-1 min-w-0">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                <p class="text-content-muted text-sm">
                    Showing <strong class="text-content-primary">{{ $products->firstItem() }}–{{ $products->lastItem() }}</strong> of <strong class="text-content-primary">{{ $products->total() }}</strong> products
                </p>
                <div class="flex items-center gap-3">
                    <select name="sort" form="filter-form" onchange="document.getElementById('filter-form').submit()"
                            class="input py-2 text-sm w-auto">
                        <option value="featured" {{ request('sort') === 'featured' ? 'selected' : '' }}>Featured First</option>
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                </div>
            </div>

            @if($products->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                @foreach($products as $product)
                @include('components.product-card', ['product' => $product])
                @endforeach
            </div>
            <div class="mt-10">
                {{ $products->withQueryString()->links() }}
            </div>
            @else
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <div class="text-6xl mb-4">📦</div>
                <h3 class="font-display text-xl font-semibold text-content-primary mb-2">No products found</h3>
                <p class="text-content-muted mb-6">Try adjusting your filters or browse other categories.</p>
                <a href="{{ route('shop.index') }}" class="btn-outline">View All Products</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
