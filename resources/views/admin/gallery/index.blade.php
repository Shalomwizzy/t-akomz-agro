@extends('layouts.admin')
@section('page-title', 'Farm Gallery')
@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="font-display font-semibold text-xl text-content-primary">Farm Gallery</h2>
        <p class="text-content-muted text-sm mt-0.5">{{ $images->total() }} images · shown on the public gallery page</p>
    </div>
</div>

@if(session('success'))
<div class="mb-5 px-4 py-3 rounded-xl text-sm text-brand-green bg-brand-green/10 border border-brand-green/20">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="mb-5 px-4 py-3 rounded-xl text-sm text-red-400 bg-red-500/10 border border-red-500/20">{{ session('error') }}</div>
@endif

{{-- Upload --}}
<div class="card p-6 mb-6" x-data="{ dragging: false }">
    <h3 class="font-semibold text-content-primary mb-4">Upload Images</h3>
    <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="label">Images (JPG, PNG, WebP — max 8MB each)</label>
                <input type="file" name="images[]" multiple accept="image/*"
                       class="input text-sm file:mr-3 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:bg-brand-green/20 file:text-brand-green file:font-medium hover:file:bg-brand-green/30 cursor-pointer">
            </div>
            <div>
                <label class="label">Category <span class="text-content-muted font-normal">(optional)</span></label>
                <select name="category" class="input text-sm">
                    <option value="general">General</option>
                    <option value="poultry">Poultry</option>
                    <option value="livestock">Livestock</option>
                    <option value="crops">Crops</option>
                    <option value="eggs">Eggs</option>
                    <option value="team">Team</option>
                    <option value="facilities">Facilities</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn-primary py-2.5 px-6">Upload</button>
    </form>
</div>

{{-- Grid --}}
@if($images->count())
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
    @foreach($images as $image)
    <div class="group relative rounded-xl overflow-hidden aspect-square bg-surface-elevated"
         style="border: 1px solid rgba(255,255,255,0.06);">
        <img src="{{ $image->url }}" alt="{{ $image->alt_text }}"
             class="w-full h-full object-cover {{ $image->is_active ? '' : 'opacity-40' }}">

        {{-- Overlay actions --}}
        <div class="absolute inset-0 bg-surface-bg/80 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-2 p-2">
            <p class="text-xs text-content-muted text-center truncate w-full">{{ $image->category }}</p>
            <div class="flex gap-2">
                {{-- Toggle visibility --}}
                <form action="{{ route('admin.gallery.toggle', $image) }}" method="POST">
                    @csrf
                    <button type="submit" title="{{ $image->is_active ? 'Hide' : 'Show' }}"
                            class="w-7 h-7 rounded-lg flex items-center justify-center text-xs
                                   {{ $image->is_active ? 'bg-yellow-500/20 text-yellow-400' : 'bg-brand-green/20 text-brand-green' }}">
                        {{ $image->is_active ? '🙈' : '👁' }}
                    </button>
                </form>
                {{-- Delete --}}
                <form action="{{ route('admin.gallery.destroy', $image) }}" method="POST"
                      onsubmit="return confirm('Delete this image? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-7 h-7 rounded-lg bg-red-500/20 text-red-400 flex items-center justify-center text-xs">✕</button>
                </form>
            </div>
        </div>

        @if(!$image->is_active)
        <div class="absolute top-1 right-1 bg-yellow-500/80 text-xs text-surface-bg px-1.5 py-0.5 rounded font-bold">Hidden</div>
        @endif
    </div>
    @endforeach
</div>

<div class="mt-6">{{ $images->links() }}</div>
@else
<div class="card p-16 text-center">
    <div class="text-5xl mb-3">🖼️</div>
    <p class="text-content-muted">No images yet. Upload some above.</p>
</div>
@endif
@endsection
