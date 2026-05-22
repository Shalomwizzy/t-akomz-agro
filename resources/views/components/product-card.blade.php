@auth
@php
    static $cachedWishlistIds = null;
    if ($cachedWishlistIds === null) {
        $cachedWishlistIds = auth()->user()->wishlistItems()->pluck('product_id')->toArray();
    }
    $isInWishlist = in_array($product->id, $cachedWishlistIds);
@endphp
@endauth

<div class="product-card flex flex-col"
     x-data="{
         adding: false,
         wishlisting: false,
         inWishlist: {{ auth()->check() && isset($isInWishlist) && $isInWishlist ? 'true' : 'false' }},
         toast(msg) {
             window.dispatchEvent(new CustomEvent('toast', { detail: { message: msg } }));
         },
         async addToCart() {
             if (this.adding) return;
             this.adding = true;
             try {
                 const res = await fetch('{{ route('cart.add') }}', {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                         'Accept': 'application/json',
                     },
                     body: JSON.stringify({ product_id: {{ $product->id }}, quantity: 1 }),
                 });
                 const data = await res.json();
                 if (data.success) {
                     this.toast(data.message);
                     window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.cartCount } }));
                 }
             } catch {
                 this.toast('Could not add to cart. Try again.');
             } finally {
                 this.adding = false;
             }
         },
         @auth
         async toggleWishlist() {
             if (this.wishlisting) return;
             this.wishlisting = true;
             try {
                 const res = await fetch('{{ route('account.wishlist.toggle') }}', {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                         'Accept': 'application/json',
                     },
                     body: JSON.stringify({ product_id: {{ $product->id }} }),
                 });
                 const data = await res.json();
                 this.inWishlist = data.added;
                 this.toast(data.message);
             } catch {
                 this.toast('Could not update wishlist. Try again.');
             } finally {
                 this.wishlisting = false;
             }
         },
         @endauth
     }">

    {{-- Image --}}
    <a href="{{ route('shop.product', [$product->category->slug, $product->slug]) }}" class="block relative overflow-hidden">
        <div class="aspect-square bg-surface-elevated overflow-hidden">
            <img src="{{ $product->primary_image_url }}"
                 alt="{{ $product->name }}"
                 loading="lazy"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        </div>

        {{-- Badges --}}
        <div class="absolute top-3 left-3 flex flex-col gap-1.5">
            @if($product->is_organic)
            <span class="badge-green text-xs">Organic</span>
            @endif
            @if($product->discount_percentage)
            <span class="badge bg-red-500/90 text-white text-xs">-{{ $product->discount_percentage }}%</span>
            @endif
        </div>

        {{-- Wishlist button --}}
        @auth
        <button type="button" @click.prevent="toggleWishlist()"
                :disabled="wishlisting"
                class="absolute top-3 right-3 w-8 h-8 rounded-full bg-surface-bg/80 backdrop-blur-sm border border-surface-border flex items-center justify-center transition-all hover:scale-110 group"
                :class="inWishlist ? 'border-red-500/50' : 'hover:border-red-500/50'"
                :title="inWishlist ? 'Remove from wishlist' : 'Add to wishlist'">
            <svg class="w-4 h-4 transition-colors"
                 :class="inWishlist ? 'text-red-400' : 'text-content-muted group-hover:text-red-400'"
                 :fill="inWishlist ? 'currentColor' : 'none'"
                 stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </button>
        @else
        <a href="{{ route('login') }}"
           class="absolute top-3 right-3 w-8 h-8 rounded-full bg-surface-bg/80 backdrop-blur-sm border border-surface-border flex items-center justify-center transition-all hover:scale-110 hover:border-red-500/50 group"
           title="Sign in to add to wishlist"
           onclick="event.stopPropagation()">
            <svg class="w-4 h-4 text-content-muted group-hover:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </a>
        @endauth

        {{-- Stock overlay --}}
        @if($product->stock <= 0)
        <div class="absolute inset-0 bg-surface-bg/70 flex items-center justify-center">
            <span class="badge-error text-sm font-semibold px-4 py-1.5">Out of Stock</span>
        </div>
        @elseif($product->stock_status === 'low_stock')
        <div class="absolute bottom-3 left-3">
            <span class="badge-warning text-xs">Only {{ $product->stock }} left</span>
        </div>
        @endif
    </a>

    {{-- Info --}}
    <div class="p-4 flex flex-col flex-1">
        <a href="{{ route('shop.category', $product->category->slug) }}" class="text-xs text-content-muted hover:text-brand-green transition-colors mb-1">
            {{ $product->category->name }}
        </a>

        <a href="{{ route('shop.product', [$product->category->slug, $product->slug]) }}" class="font-display font-semibold text-content-primary hover:text-brand-green transition-colors line-clamp-2 mb-2">
            {{ $product->name }}
        </a>

        {{-- Rating --}}
        @php $rating = $product->average_rating; $reviewCount = $product->reviews()->count(); @endphp
        @if($reviewCount > 0)
        <div class="flex items-center gap-1 mb-2">
            @for($i = 1; $i <= 5; $i++)
            <svg class="w-3 h-3 {{ $i <= round($rating) ? 'text-yellow-400' : 'text-surface-elevated' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            @endfor
            <span class="text-xs text-content-muted">({{ $reviewCount }})</span>
        </div>
        @endif

        {{-- Price --}}
        <div class="flex items-baseline gap-2 mt-auto">
            <span class="font-bold text-brand-green text-lg">{{ $product->formatted_price }}</span>
            @if($product->formatted_compare_price)
            <span class="text-content-muted text-sm line-through">{{ $product->formatted_compare_price }}</span>
            @endif
            <span class="text-content-muted text-xs ml-auto">{{ $product->unit }}</span>
        </div>

        {{-- Add to Cart --}}
        @if($product->stock > 0)
        <button type="button" @click="addToCart()"
                :disabled="adding"
                class="mt-3 btn-primary w-full py-2 sm:py-2.5 text-xs sm:text-sm flex items-center justify-center gap-1.5 sm:gap-2 disabled:opacity-60 whitespace-nowrap overflow-hidden">
            <svg x-show="!adding" class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <svg x-show="adding" x-cloak class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
            <span x-text="adding ? 'Adding...' : 'Buy Now'" class="truncate"></span>
        </button>
        @else
        <button disabled class="mt-3 w-full py-2 sm:py-2.5 text-xs sm:text-sm font-medium rounded-xl bg-surface-elevated text-content-muted cursor-not-allowed whitespace-nowrap">
            Out of Stock
        </button>
        @endif
    </div>
</div>
