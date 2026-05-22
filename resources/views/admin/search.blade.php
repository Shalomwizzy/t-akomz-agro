@extends('layouts.admin')

@section('page-title', $q ? 'Search: ' . $q : 'Search')

@section('content')
<div class="max-w-4xl space-y-6">

    {{-- Search bar --}}
    <form action="{{ route('admin.search') }}" method="GET" class="flex gap-3">
        <div class="relative flex-1">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-content-muted pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="q" value="{{ $q }}" autofocus
                   placeholder="Search products, orders, customers…"
                   class="input pl-12 pr-4 py-3 text-base w-full">
        </div>
        <button type="submit" class="btn-primary px-6 py-3">Search</button>
    </form>

    @if($q && strlen($q) >= 2)

    {{-- Summary --}}
    <p class="text-content-muted text-sm">
        @if($total > 0)
            Found <strong class="text-content-primary">{{ $total }}</strong> results for <strong class="text-content-primary">"{{ $q }}"</strong>
        @else
            No results for <strong class="text-content-primary">"{{ $q }}"</strong>
        @endif
    </p>

    {{-- Products --}}
    @if($products->isNotEmpty())
    <div class="card overflow-hidden">
        <div class="px-5 py-3 border-b border-border flex items-center justify-between">
            <h3 class="font-semibold text-content-primary text-sm">Products <span class="text-content-muted font-normal">({{ $products->count() }})</span></h3>
            <a href="{{ route('admin.products.index') }}?search={{ urlencode($q) }}" class="text-xs text-brand-green hover:underline">View all →</a>
        </div>
        <div class="divide-y divide-border">
            @foreach($products as $product)
            <a href="{{ route('admin.products.edit', $product) }}" class="flex items-center gap-4 px-5 py-3 hover:bg-surface-elevated transition-colors group">
                <img src="{{ $product->primary_image_url }}" alt="" class="w-10 h-10 rounded-lg object-cover flex-shrink-0 bg-surface-card">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-content-primary group-hover:text-brand-green transition-colors truncate">{{ $product->name }}</p>
                    <p class="text-xs text-content-muted">{{ $product->category?->name }} · ₦{{ number_format($product->price, 2) }}</p>
                </div>
                <span class="text-xs px-2 py-1 rounded-full {{ $product->is_active ? 'text-brand-green bg-brand-green/10' : 'text-content-muted bg-white/5' }}">
                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                </span>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Orders --}}
    @if($orders->isNotEmpty())
    <div class="card overflow-hidden">
        <div class="px-5 py-3 border-b border-border flex items-center justify-between">
            <h3 class="font-semibold text-content-primary text-sm">Orders <span class="text-content-muted font-normal">({{ $orders->count() }})</span></h3>
            <a href="{{ route('admin.orders.index') }}?search={{ urlencode($q) }}" class="text-xs text-brand-green hover:underline">View all →</a>
        </div>
        <div class="divide-y divide-border">
            @foreach($orders as $order)
            <a href="{{ route('admin.orders.show', $order) }}" class="flex items-center justify-between gap-4 px-5 py-3 hover:bg-surface-elevated transition-colors group">
                <div class="min-w-0">
                    <p class="text-sm font-medium font-mono text-content-primary group-hover:text-brand-green transition-colors">{{ $order->order_number }}</p>
                    <p class="text-xs text-content-muted truncate">{{ $order->customer_name }} · {{ $order->customer_email }}</p>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    <span class="text-sm font-semibold text-content-primary">₦{{ number_format($order->total, 0) }}</span>
                    <span class="text-xs px-2 py-0.5 rounded-full border
                        @if($order->status === 'DELIVERED') text-brand-green border-brand-green/30 bg-brand-green/10
                        @elseif($order->status === 'CANCELLED') text-red-400 border-red-400/30 bg-red-400/10
                        @else text-yellow-400 border-yellow-400/30 bg-yellow-400/10 @endif">
                        {{ $order->status }}
                    </span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Customers --}}
    @if($customers->isNotEmpty())
    <div class="card overflow-hidden">
        <div class="px-5 py-3 border-b border-border flex items-center justify-between">
            <h3 class="font-semibold text-content-primary text-sm">Customers <span class="text-content-muted font-normal">({{ $customers->count() }})</span></h3>
            <a href="{{ route('admin.customers.index') }}?search={{ urlencode($q) }}" class="text-xs text-brand-green hover:underline">View all →</a>
        </div>
        <div class="divide-y divide-border">
            @foreach($customers as $customer)
            <a href="{{ route('admin.customers.show', $customer) }}" class="flex items-center gap-4 px-5 py-3 hover:bg-surface-elevated transition-colors group">
                <img src="{{ $customer->avatar_url }}" alt="" class="w-9 h-9 rounded-full object-cover flex-shrink-0">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-content-primary group-hover:text-brand-green transition-colors">{{ $customer->name }}</p>
                    <p class="text-xs text-content-muted">{{ $customer->email }}{{ $customer->phone ? ' · ' . $customer->phone : '' }}</p>
                </div>
                <p class="text-xs text-content-muted flex-shrink-0">Joined {{ $customer->created_at->format('M Y') }}</p>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    @if($total === 0)
    <div class="card py-16 text-center">
        <svg class="w-10 h-10 text-content-muted mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <p class="text-content-secondary font-medium">No results found</p>
        <p class="text-content-muted text-sm mt-1">Try a different search term</p>
    </div>
    @endif

    @elseif($q)
    <p class="text-content-muted text-sm">Please enter at least 2 characters to search.</p>
    @endif

</div>
@endsection
