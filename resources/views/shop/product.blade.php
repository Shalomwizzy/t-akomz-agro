@extends('layouts.app')

@section('title', $product->meta_title ?? $product->name . ' - T-Akomz Agro Estates')
@section('meta_description', $product->meta_description ?? $product->short_description)

@section('content')
<div class="container-custom py-8"
     x-data="{
         activeImage: '{{ $product->primary_image_url }}',
         activeTab: 'description',
         qty: 1,
         adding: false,
         wishlisting: false,
         inWishlist: {{ in_array($product->id, $wishlistIds) ? 'true' : 'false' }},
         setImage(url) { this.activeImage = url; },
         increaseQty() { if (this.qty < {{ $product->stock }}) this.qty++; },
         decreaseQty() { if (this.qty > 1) this.qty--; },
         toast(msg, type = 'success') {
             window.dispatchEvent(new CustomEvent('toast', { detail: { message: msg, type } }));
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
                     body: JSON.stringify({ product_id: {{ $product->id }}, quantity: this.qty }),
                 });
                 const data = await res.json();
                 if (data.success) {
                     this.toast(data.message);
                     window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.cartCount } }));
                 }
             } catch {
                 this.toast('Could not add to cart. Try again.', 'error');
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
                 this.toast('Could not update wishlist.', 'error');
             } finally {
                 this.wishlisting = false;
             }
         },
         @endauth
     }">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-content-muted mb-6">
        <a href="{{ route('home') }}" class="hover:text-brand-green transition-colors">Home</a>
        <span>/</span>
        <a href="{{ route('shop.index') }}" class="hover:text-brand-green transition-colors">Shop</a>
        <span>/</span>
        <a href="{{ route('shop.category', $category->slug) }}" class="hover:text-brand-green transition-colors">{{ $category->name }}</a>
        <span>/</span>
        <span class="text-content-primary truncate max-w-48">{{ $product->name }}</span>
    </nav>

    {{-- ─── PRODUCT MAIN SECTION ────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-16">

        {{-- Left: Image Gallery --}}
        <div class="space-y-3">
            {{-- Main Image --}}
            <div class="aspect-square bg-surface-card rounded-2xl overflow-hidden border border-surface-border">
                <img :src="activeImage" alt="{{ $product->name }}"
                     class="w-full h-full object-cover transition-opacity duration-300"
                     loading="eager">
            </div>

            {{-- Thumbnails --}}
            @if($product->images->count() > 1)
            <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide">
                @foreach($product->images as $image)
                <button @click="setImage('{{ asset('storage/' . $image->url) }}')"
                        :class="activeImage === '{{ asset('storage/' . $image->url) }}' ? 'ring-2 ring-brand-green' : 'ring-1 ring-surface-border'"
                        class="flex-shrink-0 w-16 h-16 rounded-xl overflow-hidden bg-surface-card transition-all">
                    <img src="{{ asset('storage/' . $image->url) }}" alt="" class="w-full h-full object-cover">
                </button>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Right: Product Info --}}
        <div class="flex flex-col">
            {{-- Category + Badges --}}
            <div class="flex items-center gap-2 mb-3">
                <a href="{{ route('shop.category', $category->slug) }}" class="text-sm text-brand-green hover:underline">
                    {{ $category->name }}
                </a>
                @if($product->is_organic)
                <span class="badge-green text-xs">Organic</span>
                @endif
                @if($product->is_featured)
                <span class="badge bg-brand-green/20 text-brand-green text-xs">Featured</span>
                @endif
            </div>

            {{-- Product Name --}}
            <h1 class="font-display text-3xl md:text-4xl font-bold text-content-primary leading-tight mb-3">
                {{ $product->name }}
            </h1>

            {{-- Rating --}}
            @php $rating = $product->reviews->avg('rating') ?? 0; $reviewCount = $product->reviews->count(); @endphp
            <div class="flex items-center gap-3 mb-4">
                <div class="flex items-center gap-0.5">
                    @for($i = 1; $i <= 5; $i++)
                    <svg class="w-4 h-4 {{ $i <= round($rating) ? 'text-yellow-400' : 'text-surface-elevated' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    @endfor
                </div>
                <a href="#reviews" class="text-sm text-content-muted hover:text-brand-green transition-colors">
                    {{ $reviewCount }} {{ Str::plural('review', $reviewCount) }}
                </a>
                @if($product->sku)
                <span class="text-sm text-content-muted">SKU: {{ $product->sku }}</span>
                @endif
            </div>

            {{-- Price --}}
            <div class="flex items-baseline gap-3 mb-2">
                <span class="font-bold text-brand-green text-4xl">{{ $product->formatted_price }}</span>
                @if($product->formatted_compare_price)
                <span class="text-content-muted text-xl line-through">{{ $product->formatted_compare_price }}</span>
                <span class="badge bg-red-500/20 text-red-400 text-sm">-{{ $product->discount_percentage }}% OFF</span>
                @endif
            </div>
            <p class="text-content-muted text-sm mb-6">Per {{ $product->unit }}</p>

            {{-- Stock Status --}}
            <div class="flex items-center gap-2 mb-6">
                @if($product->stock > 0)
                    @if($product->stock_status === 'low_stock')
                    <span class="inline-flex items-center gap-1.5 text-sm text-yellow-400">
                        <span class="w-2 h-2 rounded-full bg-yellow-400 animate-pulse"></span>
                        Only {{ $product->stock }} left in stock
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1.5 text-sm text-brand-green">
                        <span class="w-2 h-2 rounded-full bg-brand-green"></span>
                        In Stock ({{ $product->stock }} available)
                    </span>
                    @endif
                @else
                <span class="inline-flex items-center gap-1.5 text-sm text-red-400">
                    <span class="w-2 h-2 rounded-full bg-red-400"></span>
                    Out of Stock
                </span>
                @endif
            </div>

            {{-- Short Description --}}
            @if($product->short_description)
            <p class="text-content-secondary text-sm leading-relaxed mb-6">{{ $product->short_description }}</p>
            @endif

            {{-- Add to Cart --}}
            @if($product->stock > 0)
            <div class="space-y-4 mb-6">
                {{-- Quantity --}}
                <div class="flex items-center gap-4">
                    <label class="text-sm text-content-secondary">Quantity:</label>
                    <div class="flex items-center gap-0 border border-surface-border rounded-xl overflow-hidden">
                        <button type="button" @click="decreaseQty()"
                                class="w-10 h-10 flex items-center justify-center text-content-secondary hover:text-brand-green hover:bg-surface-elevated transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                        </button>
                        <input type="number" x-model.number="qty" min="1" max="{{ $product->stock }}"
                               class="w-14 h-10 text-center bg-transparent text-content-primary text-sm border-x border-surface-border focus:outline-none">
                        <button type="button" @click="increaseQty()"
                                class="w-10 h-10 flex items-center justify-center text-content-secondary hover:text-brand-green hover:bg-surface-elevated transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" @click="addToCart()" :disabled="adding"
                            class="btn-primary flex-1 py-3.5 text-sm gap-2 flex items-center justify-center disabled:opacity-60">
                        <svg x-show="!adding" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <svg x-show="adding" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                        <span x-text="adding ? 'Adding...' : 'Add to Cart'"></span>
                    </button>
                    @auth
                    <button type="button" @click="toggleWishlist()" :disabled="wishlisting"
                            class="w-12 h-12 flex-shrink-0 flex items-center justify-center rounded-xl border transition-all disabled:opacity-60"
                            :class="inWishlist ? 'border-red-500/50 bg-red-500/10 text-red-400' : 'border-surface-border bg-surface-card text-content-muted hover:border-red-500/50 hover:text-red-400'"
                            :title="inWishlist ? 'Remove from wishlist' : 'Add to Wishlist'">
                        <svg class="w-5 h-5" :fill="inWishlist ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </button>
                    @endauth
                </div>
            </div>
            @else
            <button disabled class="w-full py-3.5 text-sm font-medium rounded-xl bg-surface-elevated text-content-muted cursor-not-allowed mb-6">
                Out of Stock — Notify Me
            </button>
            @endif

            {{-- Meta Info --}}
            <div class="border-t border-surface-border pt-4 space-y-2">
                @if($product->weight)
                <div class="flex gap-3 text-sm">
                    <span class="text-content-muted w-24">Weight:</span>
                    <span class="text-content-secondary">{{ $product->weight }}kg</span>
                </div>
                @endif
                @if($product->tags && count($product->tags))
                <div class="flex gap-3 text-sm">
                    <span class="text-content-muted w-24">Tags:</span>
                    <div class="flex flex-wrap gap-1">
                        @foreach($product->tags as $tag)
                        <span class="text-xs px-2 py-0.5 bg-surface-elevated rounded-full text-content-muted">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
                <div class="flex gap-3 text-sm">
                    <span class="text-content-muted w-24">Delivery:</span>
                    <span class="text-content-secondary">2–5 business days Nigeria-wide</span>
                </div>
                <div class="flex gap-3 text-sm">
                    <span class="text-content-muted w-24">Share:</span>
                    <div class="flex gap-2">
                        <a href="https://wa.me/?text={{ urlencode($product->name . ' - ' . request()->url()) }}" target="_blank" rel="noopener noreferrer" 
                           class="text-content-muted hover:text-green-400 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M11.993 1.985C6.495 1.985 2 6.48 2 11.978c0 1.837.487 3.616 1.416 5.181L2 22l4.95-1.392C8.507 21.516 10.24 22 12.007 22 17.505 22 22 17.505 22 12.007 22 6.509 17.491 1.985 11.993 1.985z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── TABS ─────────────────────────────────────────────────────────── --}}
    <div class="mb-16">
        {{-- Tab Nav --}}
        <div class="flex gap-1 border-b border-surface-border mb-8 overflow-x-auto scrollbar-hide">
            @foreach([['description', 'Description'], ['nutrition', 'Nutrition'], ['reviews', 'Reviews (' . $reviewCount . ')'], ['delivery', 'Delivery & Returns']] as [$tab, $label])
            <button @click="activeTab = '{{ $tab }}'"
                    :class="activeTab === '{{ $tab }}' ? 'text-brand-green border-brand-green' : 'text-content-muted border-transparent hover:text-content-secondary'"
                    class="flex-shrink-0 px-5 py-3 text-sm font-medium border-b-2 transition-colors -mb-px">
                {{ $label }}
            </button>
            @endforeach
        </div>

        {{-- Description Tab --}}
        <div x-show="activeTab === 'description'" x-transition>
            <div class="prose prose-invert prose-green max-w-none text-content-secondary">
                {!! $product->description !!}
            </div>
        </div>

        {{-- Nutrition Tab --}}
        <div x-show="activeTab === 'nutrition'" x-transition>
            @if($product->nutrition_facts && count($product->nutrition_facts))
            <div class="card p-6 max-w-md">
                <h3 class="font-display font-semibold text-content-primary mb-4">Nutrition Facts</h3>
                <div class="space-y-2">
                    @foreach($product->nutrition_facts as $key => $value)
                    <div class="flex justify-between text-sm border-b border-surface-border pb-2">
                        <span class="text-content-secondary capitalize">{{ str_replace('_', ' ', $key) }}</span>
                        <span class="text-content-primary font-medium">{{ $value }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <p class="text-content-muted">Nutrition information not available for this product.</p>
            @endif
        </div>

        {{-- Reviews Tab --}}
        <div x-show="activeTab === 'reviews'" x-transition id="reviews">
            @php $approvedReviews = $product->reviews; @endphp

            {{-- Flash messages --}}
            @if(session('success') && str_contains(session('success'), 'review'))
            <div class="mb-6 bg-brand-green/10 border border-brand-green/20 text-brand-green text-sm px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
            @endif
            @if(session('error') && str_contains(session('error'), 'review'))
            <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-400 text-sm px-4 py-3 rounded-xl">
                {{ session('error') }}
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Left: Reviews list --}}
                <div class="lg:col-span-2 space-y-5">
                    @if($approvedReviews->count())
                    {{-- Rating Summary --}}
                    <div class="card p-5 flex items-center gap-6">
                        <div class="text-center flex-shrink-0">
                            <div class="font-display text-5xl font-bold text-content-primary">{{ number_format($rating, 1) }}</div>
                            <div class="flex justify-center gap-0.5 my-1.5">
                                @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= round($rating) ? 'text-yellow-400' : 'text-surface-elevated' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                            <p class="text-xs text-content-muted">{{ $approvedReviews->count() }} {{ Str::plural('review', $approvedReviews->count()) }}</p>
                        </div>
                        {{-- Rating bars --}}
                        <div class="flex-1 space-y-1.5">
                            @for($s = 5; $s >= 1; $s--)
                            @php $cnt = $approvedReviews->where('rating', $s)->count(); $pct = $approvedReviews->count() ? round($cnt / $approvedReviews->count() * 100) : 0; @endphp
                            <div class="flex items-center gap-2 text-xs text-content-muted">
                                <span class="w-3">{{ $s }}</span>
                                <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <div class="flex-1 h-2 bg-surface-elevated rounded-full overflow-hidden">
                                    <div class="h-full bg-yellow-400/60 rounded-full" style="width: {{ $pct }}%"></div>
                                </div>
                                <span class="w-6 text-right">{{ $cnt }}</span>
                            </div>
                            @endfor
                        </div>
                    </div>

                    @foreach($approvedReviews as $review)
                    <div class="card p-5">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $review->user->avatar_url }}" alt="{{ $review->user->name }}"
                                     class="w-9 h-9 rounded-full object-cover flex-shrink-0">
                                <div>
                                    <p class="text-sm font-medium text-content-primary">{{ $review->user->name }}</p>
                                    <p class="text-xs text-content-muted">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="flex gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-surface-elevated' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                        </div>
                        @if($review->title)
                        <h4 class="text-sm font-semibold text-content-primary mb-1">{{ $review->title }}</h4>
                        @endif
                        <p class="text-sm text-content-secondary leading-relaxed">{{ $review->body }}</p>
                    </div>
                    @endforeach

                    @else
                    <div class="text-center py-12 card">
                        <div class="text-4xl mb-3">⭐</div>
                        <p class="text-content-primary font-medium mb-1">No reviews yet</p>
                        <p class="text-content-muted text-sm">Be the first to share your experience.</p>
                    </div>
                    @endif
                </div>

                {{-- Right: Write a Review --}}
                <div>
                    @auth
                        @if($hasReviewed)
                        <div class="card p-5 text-center">
                            <div class="text-3xl mb-2">✅</div>
                            <p class="text-sm font-medium text-content-primary mb-1">Review submitted</p>
                            <p class="text-xs text-content-muted">Your review is pending approval. Thank you!</p>
                        </div>
                        @else
                        <div class="card p-5" x-data="{ rating: 0, hover: 0 }">
                            <h3 class="font-semibold text-content-primary mb-4">Write a Review</h3>
                            <form action="{{ route('reviews.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="rating" :value="rating">

                                {{-- Star Picker --}}
                                <div>
                                    <label class="label">Your Rating <span class="text-red-400">*</span></label>
                                    <div class="flex gap-1 mt-1">
                                        @for($i = 1; $i <= 5; $i++)
                                        <button type="button"
                                                @click="rating = {{ $i }}"
                                                @mouseenter="hover = {{ $i }}"
                                                @mouseleave="hover = 0"
                                                class="focus:outline-none transition-transform hover:scale-110">
                                            <svg class="w-8 h-8 transition-colors"
                                                 :class="(hover || rating) >= {{ $i }} ? 'text-yellow-400' : 'text-surface-elevated'"
                                                 fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </button>
                                        @endfor
                                    </div>
                                    @error('rating')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="label">Title</label>
                                    <input type="text" name="title" value="{{ old('title') }}"
                                           class="input text-sm @error('title') border-red-500 @enderror"
                                           placeholder="Great product!">
                                </div>

                                <div>
                                    <label class="label">Review <span class="text-red-400">*</span></label>
                                    <textarea name="body" rows="4"
                                              class="input resize-none text-sm @error('body') border-red-500 @enderror"
                                              placeholder="Share your experience with this product..." required>{{ old('body') }}</textarea>
                                    @error('body')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <button type="submit" class="btn-primary w-full py-3 text-sm"
                                        x-bind:disabled="rating === 0"
                                        :class="rating === 0 ? 'opacity-50 cursor-not-allowed' : ''">
                                    Submit Review
                                </button>
                            </form>
                        </div>
                        @endif
                    @else
                    <div class="card p-5 text-center">
                        <div class="text-3xl mb-3">✍️</div>
                        <p class="text-sm font-medium text-content-primary mb-1">Have this product?</p>
                        <p class="text-xs text-content-muted mb-4">Sign in to leave a review.</p>
                        <a href="{{ route('login') }}" class="btn-primary text-sm px-6 py-2.5">Sign In to Review</a>
                    </div>
                    @endauth
                </div>
            </div>
        </div>

        {{-- Delivery Tab --}}
        <div x-show="activeTab === 'delivery'" x-transition>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="card p-5">
                    <h4 class="font-semibold text-content-primary mb-3 flex items-center gap-2">
                        <span class="text-xl">🚚</span> Delivery Information
                    </h4>
                    <ul class="space-y-2 text-sm text-content-secondary">
                        <li class="flex gap-2"><span class="text-brand-green">✓</span> Standard delivery: 2–5 business days</li>
                        <li class="flex gap-2"><span class="text-brand-green">✓</span> Express delivery: 1–2 business days</li>
                        <li class="flex gap-2"><span class="text-brand-green">✓</span> Pickup available at our farm</li>
                        <li class="flex gap-2"><span class="text-brand-green">✓</span> Nationwide delivery across Nigeria</li>
                        <li class="flex gap-2"><span class="text-brand-green">✓</span> Cold-chain packaging for fresh produce</li>
                    </ul>
                </div>
                <div class="card p-5">
                    <h4 class="font-semibold text-content-primary mb-3 flex items-center gap-2">
                        <span class="text-xl">↩️</span> Returns Policy
                    </h4>
                    <ul class="space-y-2 text-sm text-content-secondary">
                        <li class="flex gap-2"><span class="text-brand-green">✓</span> Report issues within 24 hours of delivery</li>
                        <li class="flex gap-2"><span class="text-brand-green">✓</span> Full refund for damaged/spoiled items</li>
                        <li class="flex gap-2"><span class="text-brand-green">✓</span> Replacement or store credit options</li>
                        <li class="flex gap-2"><span class="text-brand-green">✓</span> Photo evidence required for claims</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── RELATED PRODUCTS ────────────────────────────────────────────── --}}
    @if($related->count())
    <div>
        <h2 class="font-display text-2xl font-bold text-content-primary mb-6">You May Also Like</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($related as $relProduct)
            @include('components.product-card', ['product' => $relProduct])
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
