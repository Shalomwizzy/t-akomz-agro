@extends('layouts.app')

@section('title', 'My Wishlist - T-Akomz Agro Estates')

@section('content')
<div class="bg-surface-card border-b border-surface-border">
    <div class="container-custom py-8">
        <h1 class="font-display text-3xl font-bold text-content-primary">My Wishlist</h1>
        <p class="text-content-muted mt-1">{{ $wishlist->count() }} saved {{ Str::plural('product', $wishlist->count()) }}</p>
    </div>
</div>

<div class="container-custom py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        @include('account.partials.sidebar')

        <div class="flex-1 min-w-0">
            @if($wishlist->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                @foreach($wishlist as $item)
                <div class="card flex flex-col relative group">
                    {{-- Remove --}}
                    <form action="{{ route('account.wishlist.toggle') }}" method="POST" class="absolute top-3 right-3 z-10">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-full bg-surface-bg/80 text-red-400 hover:bg-red-500/20 transition-colors"
                                title="Remove from wishlist">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        </button>
                    </form>

                    @include('components.product-card', ['product' => $item->product])
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-24">
                <div class="text-6xl mb-4">❤️</div>
                <h3 class="font-display text-xl font-semibold text-content-primary mb-2">Your wishlist is empty</h3>
                <p class="text-content-muted mb-6">Save products you love and buy them later.</p>
                <a href="{{ route('shop.index') }}" class="btn-primary px-8 py-3">Browse Products</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
