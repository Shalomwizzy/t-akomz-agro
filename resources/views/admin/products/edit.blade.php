@extends('layouts.admin')

@section('page-title', 'Edit: ' . $product->name)

@section('content')
<div class="max-w-4xl" x-data="productEditForm()">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.products.index') }}" class="text-content-muted hover:text-brand-green transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h2 class="font-display font-semibold text-content-primary truncate">{{ $product->name }}</h2>
    </div>

    {{-- ── UPDATE FORM (no nested forms inside) ── --}}
    <form id="product-update-form"
          action="{{ route('admin.products.update', $product) }}"
          method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        {{-- AI Generate Banner --}}
        <div class="card p-4 mb-5 border-brand-green/20 bg-brand-green/5">
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <div class="flex items-center gap-3 flex-1">
                    <div class="w-9 h-9 rounded-full bg-brand-green/15 border border-brand-green/30 flex items-center justify-center flex-shrink-0">
                        <x-logo class="w-5 h-5" />
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-content-primary">AI Content Generator</p>
                        <p class="text-xs text-content-muted">Regenerate description, SEO fields, and nutrition facts. Current content will be replaced.</p>
                    </div>
                </div>
                <button type="button" @click="generateAllContent()"
                        :disabled="aiLoading"
                        class="flex items-center gap-2 px-4 py-2 bg-brand-green text-surface-bg rounded-lg text-sm font-medium hover:bg-brand-dark-green transition-colors disabled:opacity-50 flex-shrink-0">
                    <svg x-show="!aiLoading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <svg x-show="aiLoading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    <span x-text="aiLoading ? 'Generating…' : 'Regenerate with AI'"></span>
                </button>
            </div>
            <div x-show="aiError" x-cloak class="mt-3 p-2 bg-red-500/10 border border-red-500/20 rounded-lg text-xs text-red-400" x-text="aiError"></div>
            <div x-show="aiSuccess" x-cloak class="mt-3 p-2 bg-brand-green/10 border border-brand-green/20 rounded-lg text-xs text-brand-green">✓ Fields updated. Review and click Save Changes.</div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ── MAIN COLUMN ── --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Basic Info --}}
                <div class="card p-5">
                    <h3 class="font-semibold text-content-primary mb-4">Basic Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="label">Product Name <span class="text-red-400">*</span></label>
                            <input type="text" name="name" id="edit-product-name" value="{{ old('name', $product->name) }}"
                                   class="input @error('name') border-red-500 @enderror" required
                                   @input="slug = $event.target.value.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/(^-|-$)/g,'')">
                            @error('name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="label">Slug</label>
                            <input type="text" name="slug" :value="slug" class="input text-sm font-mono">
                        </div>
                        <div>
                            <label class="label">Short Description</label>
                            <textarea name="short_description" id="edit-short-description" rows="2"
                                      class="input resize-none">{{ old('short_description', $product->short_description) }}</textarea>
                        </div>
                        <div>
                            <label class="label">Full Description</label>
                            <textarea name="description" id="edit-description" rows="8"
                                      class="input resize-y font-mono text-sm">{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Images --}}
                <div class="card p-5">
                    <h3 class="font-semibold text-content-primary mb-4">Images</h3>

                    {{-- Existing images — delete via AJAX, no nested form --}}
                    @if($product->images->count())
                    <div id="existing-images" class="flex gap-3 flex-wrap mb-4">
                        @foreach($product->images as $image)
                        <div class="relative group" id="img-wrapper-{{ $image->id }}">
                            <img src="{{ asset('storage/' . $image->url) }}" alt=""
                                 class="w-24 h-24 object-cover rounded-xl border border-surface-border">
                            @if($image->is_primary)
                            <span class="absolute bottom-1 left-1 text-[10px] bg-brand-green text-surface-bg px-1.5 py-0.5 rounded font-semibold">Primary</span>
                            @endif
                            <button type="button"
                                    onclick="deleteImage({{ $image->id }}, '{{ route('admin.products.image.delete', [$product, $image]) }}')"
                                    class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 hover:bg-red-600 rounded-full text-white text-sm font-bold flex items-center justify-center shadow-md transition-colors"
                                    title="Remove image">
                                &times;
                            </button>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div id="existing-images" class="flex gap-3 flex-wrap mb-4"></div>
                    @endif

                    {{-- New image picker --}}
                    <div class="border-2 border-dashed border-surface-border rounded-xl p-5 text-center cursor-pointer hover:border-brand-green/40 transition-colors"
                         onclick="document.getElementById('new-images').click()">
                        <svg class="w-8 h-8 text-content-muted mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm text-content-muted">Click to add more images</p>
                        <p class="text-xs text-content-muted mt-1">JPG, PNG, WebP — up to 5MB each</p>
                    </div>
                    <input type="file" id="new-images" name="images[]" multiple accept="image/*" class="sr-only">

                    {{-- New image previews (rendered by JS) --}}
                    <div id="new-image-previews" class="flex gap-3 flex-wrap mt-3"></div>
                </div>

                {{-- Nutrition --}}
                <div class="card p-5">
                    <h3 class="font-semibold text-content-primary mb-4">Nutrition Facts
                        <span class="text-content-muted font-normal text-sm">(AI can fill this)</span>
                    </h3>
                    <div class="grid grid-cols-2 gap-3">
                        @php $nf = $product->nutrition_facts ?? []; @endphp
                        @foreach(['calories','protein','fat','carbohydrates','fiber','sugar','sodium'] as $nutrient)
                        <div>
                            <label class="label capitalize">{{ $nutrient }}</label>
                            <input type="text" name="nutrition_facts[{{ $nutrient }}]"
                                   id="edit-nutrition-{{ $nutrient }}"
                                   value="{{ old('nutrition_facts.' . $nutrient, $nf[$nutrient] ?? '') }}"
                                   placeholder="e.g. 250 kcal"
                                   class="input text-sm py-2">
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- ── SIDEBAR ── --}}
            <div class="space-y-5">

                <div class="card p-5">
                    <h3 class="font-semibold text-content-primary mb-4">Status</h3>
                    <div class="space-y-3">
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-sm text-content-secondary">Active</span>
                            <input type="checkbox" name="is_active" value="1" class="w-4 h-4 accent-green-500"
                                   {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        </label>
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-sm text-content-secondary">Featured</span>
                            <input type="checkbox" name="is_featured" value="1" class="w-4 h-4 accent-green-500"
                                   {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                        </label>
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-sm text-content-secondary">Organic</span>
                            <input type="checkbox" name="is_organic" value="1" class="w-4 h-4 accent-green-500"
                                   {{ old('is_organic', $product->is_organic) ? 'checked' : '' }}>
                        </label>
                    </div>
                </div>

                <div class="card p-5">
                    <h3 class="font-semibold text-content-primary mb-4">Category</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="label">Category</label>
                            <select name="category_id" class="input">
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div><label class="label">Unit</label><input type="text" name="unit" value="{{ old('unit', $product->unit) }}" class="input text-sm"></div>
                        <div><label class="label">SKU</label><input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="input text-sm font-mono"></div>
                        <div><label class="label">Tags (comma separated)</label>
                            <input type="text" name="tags_input" id="edit-tags-input"
                                   value="{{ old('tags_input', implode(', ', $product->tags ?? [])) }}"
                                   class="input text-sm"></div>
                        <div><label class="label">Weight (kg)</label><input type="number" step="0.01" name="weight" value="{{ old('weight', $product->weight) }}" class="input text-sm"></div>
                    </div>
                </div>

                <div class="card p-5">
                    <h3 class="font-semibold text-content-primary mb-4">Pricing</h3>
                    <div class="space-y-3">
                        <div><label class="label">Price (₦) <span class="text-red-400">*</span></label>
                            <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" class="input" required></div>
                        <div><label class="label">Compare Price (₦)</label>
                            <input type="number" step="0.01" name="compare_price" value="{{ old('compare_price', $product->compare_price) }}" class="input"></div>
                        <div><label class="label">Wholesale Price (₦)</label>
                            <input type="number" step="0.01" name="wholesale_price" value="{{ old('wholesale_price', $product->wholesale_price) }}" class="input"></div>
                    </div>
                </div>

                <div class="card p-5">
                    <h3 class="font-semibold text-content-primary mb-4">Inventory</h3>
                    <div class="space-y-3">
                        <div><label class="label">Stock Quantity</label>
                            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="input" required></div>
                        <div><label class="label">Low Stock Threshold</label>
                            <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}" class="input"></div>
                    </div>
                </div>

                <div class="card p-5">
                    <h3 class="font-semibold text-content-primary mb-4">SEO</h3>
                    <div class="space-y-3">
                        <div><label class="label">Meta Title</label>
                            <input type="text" name="meta_title" id="edit-meta-title" value="{{ old('meta_title', $product->meta_title) }}" class="input text-sm"></div>
                        <div><label class="label">Meta Description</label>
                            <textarea name="meta_description" id="edit-meta-description" rows="2" class="input resize-none text-sm">{{ old('meta_description', $product->meta_description) }}</textarea></div>
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full py-3">Save Changes</button>

            </div>
        </div>
    </form>
    {{-- ── END UPDATE FORM ── --}}

    {{-- ── DELETE FORM — completely outside the update form ── --}}
    <form id="product-delete-form"
          action="{{ route('admin.products.destroy', $product) }}"
          method="POST"
          onsubmit="return confirm('Delete \'{{ addslashes($product->name) }}\'? This cannot be undone.')"
          class="mt-4">
        @csrf @method('DELETE')
        <button type="submit" class="btn-danger w-full py-2.5 text-sm">Delete Product</button>
    </form>

</div>

<script>
// ── Image delete via AJAX (avoids nested-form issues) ───────────────────────
function deleteImage(imageId, url) {
    if (!confirm('Remove this image?')) return;

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-HTTP-Method-Override': 'DELETE',
            'Accept': 'application/json',
        },
        body: new URLSearchParams({ _method: 'DELETE' }),
    })
    .then(r => {
        if (r.ok || r.status === 302) {
            const el = document.getElementById('img-wrapper-' + imageId);
            if (el) el.remove();
        } else {
            alert('Failed to delete image. Please try again.');
        }
    })
    .catch(() => alert('Network error. Please try again.'));
}

// ── New-image preview ───────────────────────────────────────────────────────
document.getElementById('new-images').addEventListener('change', function () {
    const container = document.getElementById('new-image-previews');
    container.innerHTML = '';

    const dt = new DataTransfer();

    Array.from(this.files).forEach((file, idx) => {
        if (!file.type.startsWith('image/')) return;

        const reader = new FileReader();
        reader.onload = e => {
            const wrapper = document.createElement('div');
            wrapper.className = 'relative group';
            wrapper.dataset.idx = idx;

            wrapper.innerHTML = `
                <img src="${e.target.result}" alt=""
                     class="w-24 h-24 object-cover rounded-xl border border-surface-border">
                <button type="button"
                        onclick="removeNewPreview(this, ${idx})"
                        class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 hover:bg-red-600 rounded-full text-white text-sm font-bold flex items-center justify-center shadow-md transition-colors"
                        title="Remove">
                    &times;
                </button>
                <p class="text-xs text-content-muted mt-1 w-24 truncate text-center">${file.name}</p>
            `;
            container.appendChild(wrapper);
        };
        reader.readAsDataURL(file);
        dt.items.add(file);
    });

    this.files = dt.files;
});

function removeNewPreview(btn, idx) {
    const input = document.getElementById('new-images');
    const dt = new DataTransfer();

    Array.from(input.files).forEach((file, i) => {
        if (i !== idx) dt.items.add(file);
    });

    input.files = dt.files;
    btn.closest('.relative').remove();

    // Re-index remaining previews
    document.querySelectorAll('#new-image-previews .relative').forEach((el, newIdx) => {
        const removeBtn = el.querySelector('button');
        if (removeBtn) removeBtn.setAttribute('onclick', `removeNewPreview(this, ${newIdx})`);
        el.dataset.idx = newIdx;
    });
}

// ── Alpine.js product form component ───────────────────────────────────────
function productEditForm() {
    return {
        slug: '{{ $product->slug }}',
        aiLoading: false,
        aiError: '',
        aiSuccess: false,

        async generateAllContent() {
            const name = document.getElementById('edit-product-name')?.value?.trim();
            const categorySelect = document.querySelector('select[name="category_id"]');
            const categoryText = categorySelect?.options[categorySelect.selectedIndex]?.text || '';

            if (!name) { this.aiError = 'Product name is missing.'; return; }

            this.aiLoading = true;
            this.aiError = '';
            this.aiSuccess = false;

            try {
                const res = await fetch('/admin/ai/product-content', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ product_name: name, category: categoryText }),
                });

                if (!res.ok) throw new Error('API error ' + res.status);
                const data = await res.json();

                const set = (id, value) => { const el = document.getElementById(id); if (el && value) el.value = value; };
                set('edit-short-description', data.short_description);
                set('edit-description', data.description);
                set('edit-meta-title', data.meta_title);
                set('edit-meta-description', data.meta_description);
                set('edit-tags-input', data.tags);

                if (data.nutrition_facts && typeof data.nutrition_facts === 'object') {
                    ['calories','protein','fat','carbohydrates','fiber','sugar','sodium'].forEach(n => {
                        const el = document.getElementById('edit-nutrition-' + n);
                        if (el && data.nutrition_facts[n]) el.value = data.nutrition_facts[n];
                    });
                }

                this.aiSuccess = true;
            } catch (e) {
                this.aiError = 'Generation failed: ' + e.message;
            }

            this.aiLoading = false;
        },
    };
}
</script>
@endsection
