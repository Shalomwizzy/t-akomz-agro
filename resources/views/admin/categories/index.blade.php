@extends('layouts.admin')

@section('page-title', 'Categories')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

    {{-- Category List --}}
    <div>
        <div class="card overflow-hidden">
            <div class="px-5 py-4 border-b border-surface-border flex items-center justify-between">
                <h2 class="font-semibold text-content-primary">All Categories</h2>
                <span class="text-xs text-content-muted">{{ $categories->count() }} total</span>
            </div>
            <div class="divide-y divide-surface-border">
                @forelse($categories as $category)
                <div class="flex items-center gap-4 px-5 py-4 hover:bg-surface-elevated/50 transition-colors">
                    <span class="text-2xl flex-shrink-0">{{ $category->icon ?? '📦' }}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-content-primary">{{ $category->name }}</p>
                        <p class="text-xs text-content-muted">{{ $category->active_products_count ?? 0 }} products · /{{ $category->slug }}</p>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        @if($category->is_active)
                        <span class="badge bg-brand-green/15 text-brand-green text-xs">Active</span>
                        @else
                        <span class="badge bg-red-500/15 text-red-400 text-xs">Hidden</span>
                        @endif
                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-xs text-brand-green hover:underline">Edit</a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                              onsubmit="return confirm('Delete {{ $category->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-400 hover:underline">Delete</button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-content-muted text-sm">No categories yet. Add one below.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Add/Edit Form --}}
    <div>
        <div class="card p-5">
            <h2 class="font-semibold text-content-primary mb-5">
                {{ isset($editCategory) ? 'Edit Category' : 'Add New Category' }}
            </h2>
            <form action="{{ isset($editCategory) ? route('admin.categories.update', $editCategory) : route('admin.categories.store') }}"
                  method="POST"
                  x-data="{ slug: '{{ isset($editCategory) ? $editCategory->slug : '' }}' }">
                @csrf
                @if(isset($editCategory)) @method('PUT') @endif

                <div class="space-y-4">
                    <div>
                        <label class="label">Name <span class="text-red-400">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $editCategory->name ?? '') }}"
                               class="input @error('name') border-red-500 @enderror" required
                               @input="slug = $event.target.value.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/(^-|-$)/g,'')">
                        @error('name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="label">Slug</label>
                        <input type="text" name="slug" :value="slug || '{{ old('slug', $editCategory->slug ?? '') }}'" class="input font-mono text-sm">
                    </div>
                    <div>
                        <label class="label">Icon (emoji)</label>
                        <input type="text" name="icon" value="{{ old('icon', $editCategory->icon ?? '') }}"
                               placeholder="🐔" class="input text-2xl" maxlength="4">
                    </div>
                    <div>
                        <label class="label">Description</label>
                        <textarea name="description" rows="2" class="input resize-none text-sm">{{ old('description', $editCategory->description ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="label">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $editCategory->sort_order ?? 0) }}" class="input text-sm">
                    </div>
                    <div>
                        <label class="label">Meta Title</label>
                        <input type="text" name="meta_title" value="{{ old('meta_title', $editCategory->meta_title ?? '') }}" class="input text-sm">
                    </div>
                    <label class="flex items-center justify-between cursor-pointer">
                        <span class="text-sm text-content-secondary">Active</span>
                        <input type="checkbox" name="is_active" value="1" class="w-4 h-4 accent-green-500"
                               {{ old('is_active', $editCategory->is_active ?? true) ? 'checked' : '' }}>
                    </label>
                    <div class="flex gap-3">
                        <button type="submit" class="btn-primary flex-1 py-2.5 text-sm">
                            {{ isset($editCategory) ? 'Save Changes' : 'Add Category' }}
                        </button>
                        @if(isset($editCategory))
                        <a href="{{ route('admin.categories.index') }}" class="btn-ghost py-2.5 px-4 text-sm">Cancel</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
