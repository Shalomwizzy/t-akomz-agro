@extends('layouts.app')
@section('title', 'Farm Journal — T-Akomz Agro Estates')
@section('meta_description', 'Read the latest farming insights, agro tips and stories from T-Akomz Agro Estates Nigeria.')

@push('head')
<style>
.blog-hero {
    background: linear-gradient(135deg, #050505 0%, #0d1a09 50%, #050505 100%);
    position: relative;
    overflow: hidden;
}
.blog-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        radial-gradient(ellipse 70% 60% at 20% 50%, rgba(184,243,151,0.06) 0%, transparent 60%),
        radial-gradient(ellipse 50% 50% at 80% 20%, rgba(111,174,75,0.04) 0%, transparent 55%);
    pointer-events: none;
}
.blog-hero-grid {
    background-image: repeating-linear-gradient(0deg, transparent, transparent 39px, rgba(184,243,151,0.03) 39px, rgba(184,243,151,0.03) 40px),
                      repeating-linear-gradient(90deg, transparent, transparent 39px, rgba(184,243,151,0.03) 39px, rgba(184,243,151,0.03) 40px);
}
.featured-card {
    position: relative;
    overflow: hidden;
    border-radius: 24px;
    background: #111;
    border: 1px solid rgba(255,255,255,0.06);
    transition: transform 0.4s ease, box-shadow 0.4s ease;
}
.featured-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 24px 60px rgba(0,0,0,0.5), 0 0 0 1px rgba(184,243,151,0.15);
}
.post-card {
    position: relative;
    overflow: hidden;
    border-radius: 16px;
    background: #111111;
    border: 1px solid rgba(255,255,255,0.06);
    transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
    display: flex;
    flex-direction: column;
}
.post-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 50px rgba(0,0,0,0.4), 0 0 0 1px rgba(184,243,151,0.2);
    border-color: rgba(184,243,151,0.15);
}
.post-card:hover .post-img {
    transform: scale(1.08);
}
.post-img {
    transition: transform 0.6s ease;
}
.cat-pill {
    display: inline-block;
    padding: 3px 12px;
    border-radius: 999px;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    background: rgba(var(--brand-green) / 0.12);
    color: rgb(var(--brand-green));
    border: 1px solid rgba(var(--brand-green) / 0.22);
}
.filter-btn {
    padding: 6px 18px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
    transition: all 0.2s;
    cursor: pointer;
    border: 1px solid rgba(255,255,255,0.08);
    color: rgba(207,207,207,0.7);
    background: transparent;
}
.filter-btn:hover, .filter-btn.active {
    background: #B8F397;
    color: #050505;
    border-color: #B8F397;
}
.divider-line {
    height: 1px;
    background: linear-gradient(to right, transparent, rgba(255,255,255,0.08), transparent);
}
</style>
@endpush

@section('content')

{{-- ── HERO ─────────────────────────────────────────────────────── --}}
<div class="blog-hero blog-hero-grid">
    <div class="container-custom py-16 sm:py-20 relative z-10">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <a href="{{ route('home') }}" class="text-xs text-content-muted hover:text-brand-green transition-colors">Home</a>
                    <span class="text-content-muted text-xs">/</span>
                    <span class="text-xs text-content-primary">Blog</span>
                </div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="h-px w-10 bg-brand-green"></div>
                    <span class="text-brand-green text-[11px] font-black uppercase tracking-[0.3em]">Farm Journal</span>
                </div>
                <h1 class="font-display font-black text-4xl sm:text-6xl md:text-7xl text-white leading-[1.02]">
                    Stories From<br><span style="background: linear-gradient(135deg, #B8F397, #6FAE4B); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Our Farm</span>
                </h1>
            </div>
            <div class="max-w-xs">
                <p class="text-content-muted text-sm leading-relaxed">
                    Insights, tips, and behind-the-scenes stories direct from the fields of T-Akomz Agro Estates.
                </p>
                @if($posts->total() > 0)
                <p class="text-brand-green text-xs font-semibold mt-3">{{ $posts->total() }} article{{ $posts->total() !== 1 ? 's' : '' }} published</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="container-custom py-12 sm:py-16">

@if($posts->count())

    {{-- ── FEATURED POST ────────────────────────────────────────────── --}}
    @php $feat = $featured ?? $posts->first(); @endphp
    <div class="mb-14">
        <div class="flex items-center gap-3 mb-5">
            <span class="text-[10px] font-black uppercase tracking-[0.25em] text-content-muted">Cover Story</span>
            <div class="h-px flex-1 bg-surface-border"></div>
        </div>
        <a href="{{ route('blog.show', $feat->slug) }}" class="featured-card group block">
            <div class="grid grid-cols-1 lg:grid-cols-12 min-h-[380px]">

                {{-- Image (8/12 cols on large) --}}
                <div class="lg:col-span-7 relative overflow-hidden min-h-64 lg:min-h-0 bg-surface-elevated">
                    @if($feat->cover_image)
                    <img src="{{ $feat->cover_image_url }}" alt="{{ $feat->title }}"
                         class="absolute inset-0 w-full h-full object-cover post-img group-hover:scale-105 transition-transform duration-700">
                    @else
                    <div class="absolute inset-0 flex items-center justify-center"
                         style="background: linear-gradient(135deg, rgba(184,243,151,0.08) 0%, rgba(17,17,17,1) 100%);">
                        <svg class="w-24 h-24 text-brand-green/15" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/></svg>
                    </div>
                    @endif
                    {{-- Cover badge --}}
                    <div class="absolute top-5 left-5 z-10">
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-[11px] font-black uppercase tracking-wider"
                              style="background: #B8F397; color: #050505;">
                            ✦ Cover Story
                        </span>
                    </div>
                    {{-- Gradient vignette on right for desktop --}}
                    <div class="hidden lg:block absolute inset-y-0 right-0 w-32 pointer-events-none"
                         style="background: linear-gradient(to right, transparent, #111111);"></div>
                </div>

                {{-- Content (5/12 cols) --}}
                <div class="lg:col-span-5 flex flex-col justify-center p-8 lg:p-10">
                    <div class="flex items-center gap-2 flex-wrap mb-5">
                        @if($feat->category)
                        <span class="cat-pill">{{ $feat->category }}</span>
                        @endif
                        <span class="text-[11px] text-content-muted">{{ $feat->reading_time }} min read</span>
                    </div>
                    <h2 class="font-display text-2xl sm:text-3xl font-black text-content-primary leading-tight mb-4 group-hover:text-brand-green transition-colors duration-300">
                        {{ $feat->title }}
                    </h2>
                    <p class="text-content-muted text-sm leading-relaxed line-clamp-3 mb-7">{{ $feat->excerpt }}</p>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ 'https://ui-avatars.com/api/?name=' . urlencode($feat->author_name ?? 'T-Akomz') . '&background=B8F397&color=050505&size=64' }}"
                                 alt="{{ $feat->author_name }}"
                                 class="w-9 h-9 rounded-full object-cover ring-2 ring-brand-green/20">
                            <div>
                                <p class="text-xs font-semibold text-content-primary">{{ $feat->author_name ?? 'T-Akomz Team' }}</p>
                                <p class="text-[11px] text-content-muted">{{ $feat->published_at?->format('d M Y') }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1.5 text-brand-green text-xs font-bold group-hover:gap-2.5 transition-all">
                            Read <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </div>
            </div>
        </a>
    </div>

    {{-- ── CATEGORY FILTER ─────────────────────────────────────────── --}}
    @if($categories->count() > 0)
    <div class="mb-10" x-data="{ active: 'all' }">
        <div class="flex items-center gap-2 flex-wrap">
            <button @click="active='all'" :class="active==='all' ? 'active' : ''" class="filter-btn">
                All Stories
            </button>
            @foreach($categories as $cat)
            <button @click="active='{{ $cat }}'" :class="active==='{{ $cat }}' ? 'active' : ''" class="filter-btn capitalize">
                {{ $cat }}
            </button>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── POSTS GRID ──────────────────────────────────────────────── --}}
    @php $gridPosts = $posts->skip(1); @endphp
    @if($gridPosts->count() > 0)
    <div class="divider-line mb-10"></div>
    <div class="flex items-center gap-3 mb-7">
        <span class="text-[10px] font-black uppercase tracking-[0.25em] text-content-muted">More Articles</span>
        <div class="h-px flex-1 bg-surface-border"></div>
        <span class="text-[10px] text-content-muted">{{ $gridPosts->count() }} stories</span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
        @foreach($gridPosts as $idx => $post)
        <a href="{{ route('blog.show', $post->slug) }}" class="post-card group">

            {{-- Image --}}
            <div class="relative overflow-hidden bg-surface-elevated" style="aspect-ratio: 16/9; flex-shrink: 0;">
                @if($post->cover_image)
                <img src="{{ $post->cover_image_url }}" alt="{{ $post->title }}"
                     class="post-img w-full h-full object-cover">
                @else
                <div class="w-full h-full flex items-center justify-center"
                     style="background: linear-gradient(135deg, rgba(184,243,151,0.07) 0%, #1a1a1a 100%);">
                    <svg class="w-10 h-10 text-brand-green/20" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                </div>
                @endif
                {{-- Article number watermark --}}
                <div class="absolute bottom-3 right-3 pointer-events-none">
                    <span class="font-mono text-[10px] font-bold" style="color: rgba(184,243,151,0.3);">
                        {{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}
                    </span>
                </div>
            </div>

            {{-- Body --}}
            <div class="flex flex-col flex-1 p-5">
                <div class="flex items-center gap-2 mb-3">
                    @if($post->category)
                    <span class="cat-pill">{{ $post->category }}</span>
                    @endif
                    <span class="text-[10px] text-content-muted ml-auto">{{ $post->reading_time }} min</span>
                </div>

                <h3 class="font-display font-bold text-content-primary group-hover:text-brand-green transition-colors duration-300 text-base leading-snug line-clamp-2 mb-2 flex-1">
                    {{ $post->title }}
                </h3>
                <p class="text-content-muted text-xs leading-relaxed line-clamp-2 mb-5">{{ $post->excerpt }}</p>

                {{-- Footer --}}
                <div class="flex items-center justify-between border-t mt-auto pt-4" style="border-color: rgba(255,255,255,0.06);">
                    <div class="flex items-center gap-2">
                        <img src="{{ 'https://ui-avatars.com/api/?name=' . urlencode($post->author_name ?? 'T') . '&background=B8F397&color=050505&size=64' }}"
                             alt="" class="w-6 h-6 rounded-full flex-shrink-0">
                        <div>
                            <p class="text-[10px] font-semibold text-content-secondary leading-none">{{ $post->author_name ?? 'T-Akomz' }}</p>
                            <p class="text-[10px] text-content-muted leading-none mt-0.5">{{ $post->published_at?->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="w-7 h-7 rounded-full flex items-center justify-center group-hover:bg-brand-green group-hover:scale-110 transition-all duration-300"
                         style="background: rgba(184,243,151,0.08); border: 1px solid rgba(184,243,151,0.15);">
                        <svg class="w-3 h-3 text-brand-green group-hover:text-surface-bg transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-12">{{ $posts->links() }}</div>

    @endif

@else
    {{-- Empty state --}}
    <div class="text-center py-28">
        <div class="w-24 h-24 rounded-3xl border flex items-center justify-center mx-auto mb-6 text-4xl"
             style="background: rgba(17,17,17,0.8); border-color: rgba(255,255,255,0.06);">📝</div>
        <h3 class="font-display text-2xl font-bold text-content-primary mb-3">No articles yet</h3>
        <p class="text-content-muted max-w-sm mx-auto text-sm leading-relaxed">
            Our team is writing up the latest insights. Check back soon for farming tips and agro news.
        </p>
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mt-8 btn-outline px-6 py-3 text-sm">
            ← Back to Home
        </a>
    </div>
@endif

</div>
@endsection
