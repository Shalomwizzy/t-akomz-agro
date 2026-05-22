@extends('layouts.app')

@section('title', ($post->meta_title ?? $post->title) . ' - T-Akomz Agro Estates')
@section('meta_description', $post->meta_description ?? $post->excerpt)

@section('content')
<div class="container-custom py-8">
    <div class="max-w-3xl mx-auto">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm text-content-muted mb-6">
            <a href="{{ route('home') }}" class="hover:text-brand-green transition-colors">Home</a>
            <span>/</span>
            <a href="{{ route('blog.index') }}" class="hover:text-brand-green transition-colors">Blog</a>
            <span>/</span>
            <span class="text-content-primary truncate max-w-xs">{{ $post->title }}</span>
        </nav>

        {{-- Category + Meta --}}
        <div class="flex items-center gap-3 mb-4">
            @if($post->category)
            <span class="badge-green text-sm">{{ $post->category }}</span>
            @endif
            <span class="text-content-muted text-sm">{{ $post->reading_time }} min read</span>
        </div>

        {{-- Title --}}
        <h1 class="font-display text-3xl md:text-5xl font-bold text-content-primary leading-tight mb-6">
            {{ $post->title }}
        </h1>

        {{-- Author & Date --}}
        <div class="flex items-center gap-4 mb-8 pb-8 border-b border-surface-border">
            <img src="{{ 'https://ui-avatars.com/api/?name=' . urlencode($post->author_name ?? 'T-Akomz') . '&background=B8F397&color=050505' }}"
                 alt="{{ $post->author_name }}"
                 class="w-12 h-12 rounded-full object-cover">
            <div>
                <p class="font-medium text-content-primary text-sm">{{ $post->author_name ?? 'T-Akomz Team' }}</p>
                <p class="text-content-muted text-xs">Published {{ $post->published_at?->format('d F Y') }}</p>
            </div>
            <div class="ml-auto flex items-center gap-2">
                <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . request()->url()) }}" target="_blank" rel="noopener noreferrer" 
                   class="w-8 h-8 flex items-center justify-center rounded-lg bg-surface-elevated text-content-muted hover:text-green-400 transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 2C6.477 2 2 6.477 2 12c0 1.89.525 3.66 1.438 5.168L2 22l4.932-1.395A9.953 9.953 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2z"/></svg>
                </a>
            </div>
        </div>

        {{-- Featured Image --}}
        @if($post->cover_image)
        <div class="rounded-2xl overflow-hidden mb-10 aspect-video">
            <img src="{{ $post->cover_image_url }}" alt="{{ $post->title }}"
                 class="w-full h-full object-cover">
        </div>
        @endif

        {{-- Content --}}
        <div class="prose prose-invert prose-green prose-lg max-w-none
                    prose-headings:font-display prose-headings:text-content-primary
                    prose-p:text-content-secondary prose-p:leading-relaxed
                    prose-a:text-brand-green prose-a:no-underline hover:prose-a:underline
                    prose-strong:text-content-primary
                    prose-blockquote:border-l-brand-green prose-blockquote:text-content-muted
                    prose-code:text-brand-green prose-code:bg-surface-elevated prose-code:px-1 prose-code:rounded
                    prose-img:rounded-2xl">
            {!! $post->content !!}
        </div>

        {{-- Tags --}}
        @if($post->tags && count($post->tags))
        <div class="flex flex-wrap gap-2 mt-10 pt-8 border-t border-surface-border">
            @foreach($post->tags as $tag)
            <span class="text-xs px-3 py-1.5 bg-surface-elevated text-content-muted rounded-full">{{ $tag }}</span>
            @endforeach
        </div>
        @endif

        {{-- Related Posts --}}
        @if(isset($related) && $related->count())
        <div class="mt-16">
            <h2 class="font-display text-2xl font-bold text-content-primary mb-6">Related Articles</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                @foreach($related as $relPost)
                <a href="{{ route('blog.show', $relPost->slug) }}" class="group card overflow-hidden">
                    @if($relPost->cover_image)
                    <div class="aspect-video overflow-hidden">
                        <img src="{{ $relPost->cover_image_url }}" alt="{{ $relPost->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    @endif
                    <div class="p-4">
                        <h3 class="font-semibold text-content-primary group-hover:text-brand-green transition-colors text-sm line-clamp-2">
                            {{ $relPost->title }}
                        </h3>
                        <p class="text-xs text-content-muted mt-2">{{ $relPost->published_at?->format('d M Y') }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- CTA --}}
        <div class="mt-16 card p-8 text-center bg-brand-green/5 border-brand-green/20">
            <h3 class="font-display text-2xl font-bold text-content-primary mb-2">Ready to Order Fresh Produce?</h3>
            <p class="text-content-muted mb-6">Farm-to-door delivery across Nigeria.</p>
            <a href="{{ route('shop.index') }}" class="btn-primary px-8 py-3.5">Shop Now</a>
        </div>
    </div>
</div>
@endsection
