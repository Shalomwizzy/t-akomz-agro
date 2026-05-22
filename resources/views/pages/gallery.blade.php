@extends('layouts.app')
@section('title', 'Farm Gallery - T-Akomz Agro Estates')
@section('meta_description', 'Take a visual tour of T-Akomz Agro Estates — our poultry units, livestock, organic gardens and farm operations in Ekiti State, Nigeria.')

@section('content')

{{-- Hero --}}
<div class="bg-surface-card border-b border-surface-border">
    <div class="container-custom py-14">
        <nav class="flex items-center gap-2 text-sm text-content-muted mb-4">
            <a href="{{ route('home') }}" class="hover:text-brand-green transition-colors">Home</a>
            <span>/</span>
            <span class="text-content-primary">Gallery</span>
        </nav>
        <h1 class="font-display text-3xl md:text-5xl font-bold text-content-primary">Farm Gallery</h1>
        <p class="text-content-muted mt-2 text-lg">A visual journey through T-Akomz Agro Estates.</p>
    </div>
</div>

<div class="container-custom py-12" x-data="{ active: 'all', lightbox: null, images: {{ json_encode($images->map(fn($img) => ['url' => $img->url, 'caption' => $img->caption, 'category' => $img->category])) }} }">

    @if($categories->count() > 0)
    {{-- Category Filter --}}
    <div class="flex flex-wrap gap-2 mb-8">
        <button @click="active = 'all'"
                :class="active === 'all' ? 'bg-brand-green text-surface-bg' : 'bg-surface-elevated text-content-secondary hover:text-content-primary'"
                class="px-4 py-1.5 rounded-full text-sm font-medium transition-colors">
            All
        </button>
        @foreach($categories as $cat)
        <button @click="active = '{{ $cat }}'"
                :class="active === '{{ $cat }}' ? 'bg-brand-green text-surface-bg' : 'bg-surface-elevated text-content-secondary hover:text-content-primary'"
                class="px-4 py-1.5 rounded-full text-sm font-medium capitalize transition-colors">
            {{ $cat }}
        </button>
        @endforeach
    </div>
    @endif

    @if($images->count())
    {{-- Masonry-style grid --}}
    <div class="columns-2 sm:columns-3 lg:columns-4 gap-3 space-y-3">
        @foreach($images as $i => $image)
        <div class="break-inside-avoid"
             x-show="active === 'all' || active === '{{ $image->category }}'"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            <div class="group relative overflow-hidden rounded-xl cursor-zoom-in"
                 @click="lightbox = {{ $i }}; $nextTick(() => document.body.classList.add('overflow-hidden'))">
                <img src="{{ $image->url }}"
                     alt="{{ $image->alt_text ?? 'T-Akomz Farm' }}"
                     loading="lazy"
                     class="w-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-surface-bg/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-3">
                    @if($image->caption)
                    <p class="text-xs text-white font-medium">{{ $image->caption }}</p>
                    @endif
                    <svg class="w-5 h-5 text-white ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                </div>
                @if($image->category && $image->category !== 'general')
                <div class="absolute top-2 left-2 bg-surface-bg/70 text-xs text-brand-green px-2 py-0.5 rounded-full capitalize font-medium">
                    {{ $image->category }}
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- Lightbox --}}
    <div x-show="lightbox !== null" x-cloak
         @keydown.escape.window="lightbox = null; document.body.classList.remove('overflow-hidden')"
         class="fixed inset-0 z-50 bg-surface-bg/95 flex items-center justify-center p-4"
         @click.self="lightbox = null; document.body.classList.remove('overflow-hidden')">
        <button @click="lightbox = null; document.body.classList.remove('overflow-hidden')"
                class="absolute top-4 right-4 w-10 h-10 rounded-full bg-surface-elevated flex items-center justify-center text-content-primary hover:text-brand-green transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <button @click="lightbox = Math.max(0, lightbox - 1)"
                x-show="lightbox > 0"
                class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-surface-elevated flex items-center justify-center text-content-primary hover:text-brand-green transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <div class="max-w-4xl max-h-screen w-full" x-show="lightbox !== null">
            <template x-if="lightbox !== null && images[lightbox]">
                <div class="text-center">
                    <img :src="images[lightbox].url" :alt="images[lightbox].caption || 'T-Akomz Farm'"
                         class="max-h-[80vh] max-w-full mx-auto rounded-xl object-contain">
                    <p x-show="images[lightbox].caption" x-text="images[lightbox].caption"
                       class="text-content-secondary text-sm mt-3"></p>
                </div>
            </template>
        </div>
        <button @click="lightbox = Math.min(images.length - 1, lightbox + 1)"
                x-show="lightbox < images.length - 1"
                class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-surface-elevated flex items-center justify-center text-content-primary hover:text-brand-green transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>
    </div>

    @else
    <div class="text-center py-24">
        <div class="text-6xl mb-4">🌿</div>
        <h3 class="font-display text-xl font-semibold text-content-primary mb-2">Gallery coming soon</h3>
        <p class="text-content-muted">We're curating the best farm photos. Check back soon.</p>
    </div>
    @endif

    {{-- Instagram CTA --}}
    @php $ig = \App\Models\SiteSetting::get('social_instagram'); @endphp
    @if($ig)
    <div class="text-center mt-14 pt-10 border-t border-surface-border">
        <p class="text-content-muted text-sm mb-4">Follow us for daily farm updates and behind-the-scenes content.</p>
        <a href="{{ $ig }}" target="_blank" rel="noopener"
           class="inline-flex items-center gap-2 btn-outline px-6 py-3 text-sm">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
            Follow on Instagram
        </a>
    </div>
    @endif
</div>
@endsection
