@extends('layouts.admin')

@section('page-title', 'Add Product')

@section('content')
<div class="max-w-4xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.products.index') }}" class="text-content-muted hover:text-brand-green transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h2 class="font-display font-semibold text-content-primary">Add New Product</h2>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" x-data="productForm()">
        @csrf

        {{-- AI Generate Banner --}}
        <div class="card p-4 mb-5 border-brand-green/20 bg-brand-green/5">
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <div class="flex items-center gap-3 flex-1">
                    <div class="w-9 h-9 rounded-full bg-brand-green/15 border border-brand-green/30 flex items-center justify-center flex-shrink-0">
                        <x-logo class="w-5 h-5" />
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-content-primary">AI Content Generator</p>
                        <p class="text-xs text-content-muted">Fill in the product name and category, then click Generate to auto-fill description, SEO fields, and nutrition facts.</p>
                    </div>
                </div>
                <div class="flex gap-2 flex-shrink-0">
                    <button type="button" @click="generateAllContent()"
                            :disabled="aiLoading"
                            class="flex items-center gap-2 px-4 py-2 bg-brand-green text-surface-bg rounded-lg text-sm font-medium hover:bg-brand-dark-green transition-colors disabled:opacity-50">
                        <svg x-show="!aiLoading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <svg x-show="aiLoading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        <span x-text="aiLoading ? 'Generating...' : 'AI Generate All'"></span>
                    </button>
                </div>
            </div>
            <div x-show="aiError" x-cloak class="mt-3 p-2 bg-red-500/10 border border-red-500/20 rounded-lg text-xs text-red-400" x-text="aiError"></div>
            <div x-show="aiSuccess" x-cloak class="mt-3 p-2 bg-brand-green/10 border border-brand-green/20 rounded-lg text-xs text-brand-green">
                ✓ AI content generated successfully. Review and edit the fields below before saving.
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Basic Info --}}
                <div class="card p-5">
                    <h3 class="font-semibold text-content-primary mb-4">Basic Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="label">Product Name <span class="text-red-400">*</span></label>
                            <input type="text" name="name" id="product-name" value="{{ old('name') }}"
                                   class="input @error('name') border-red-500 @enderror" required
                                   @input="slug = $event.target.value.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/(^-|-$)/g,'')">
                            @error('name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="label">Slug</label>
                            <input type="text" name="slug" :value="slug || '{{ old('slug') }}'" class="input text-sm font-mono">
                            <p class="text-xs text-content-muted mt-1">Auto-generated from name. Leave blank to auto-fill.</p>
                        </div>
                        <div>
                            <label class="label">Short Description</label>
                            <textarea name="short_description" id="short-description" rows="2" class="input resize-none">{{ old('short_description') }}</textarea>
                        </div>
                        <div>
                            <label class="label">Full Description</label>
                            <textarea name="description" id="description" rows="8"
                                      class="input resize-y font-mono text-sm">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Images --}}
                <div class="card p-5">
                    <h3 class="font-semibold text-content-primary mb-4">Product Images</h3>
                    <div id="image-upload-area"
                         class="border-2 border-dashed border-surface-border rounded-xl p-8 text-center hover:border-brand-green/40 transition-colors cursor-pointer"
                         onclick="document.getElementById('images-input').click()">
                        <div class="text-3xl mb-2">📷</div>
                        <p class="text-sm text-content-muted">Click to upload images</p>
                        <p class="text-xs text-content-muted mt-1">JPG, PNG up to 4MB each. First image will be primary.</p>
                    </div>
                    <input type="file" id="images-input" name="images[]" multiple accept="image/*" class="sr-only"
                           @change="previewImages">
                    <div id="image-previews" class="flex gap-3 flex-wrap mt-4" x-show="previews.length > 0">
                        <template x-for="(img, idx) in previews" :key="idx">
                            <div class="relative w-20 h-20">
                                <img :src="img" class="w-20 h-20 object-cover rounded-xl">
                                <button type="button" @click="removePreview(idx)"
                                        class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full text-white text-xs flex items-center justify-center">×</button>
                                <span x-show="idx === 0" class="absolute bottom-0.5 left-0.5 text-xs bg-brand-green text-surface-bg px-1 rounded">Primary</span>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Nutrition --}}
                <div class="card p-5">
                    <h3 class="font-semibold text-content-primary mb-4">Nutrition Facts <span class="text-content-muted font-normal text-sm">(optional — AI can fill this)</span></h3>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach(['calories','protein','fat','carbohydrates','fiber','sugar','sodium'] as $nutrient)
                        <div>
                            <label class="label capitalize">{{ $nutrient }}</label>
                            <input type="text" name="nutrition_facts[{{ $nutrient }}]" id="nutrition-{{ $nutrient }}"
                                   value="{{ old('nutrition_facts.' . $nutrient) }}"
                                   placeholder="e.g. 250 kcal" class="input text-sm py-2">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-5">

                {{-- Status --}}
                <div class="card p-5">
                    <h3 class="font-semibold text-content-primary mb-4">Status & Visibility</h3>
                    <div class="space-y-3">
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-sm text-content-secondary">Active</span>
                            <input type="checkbox" name="is_active" value="1" class="w-4 h-4 accent-green-500" {{ old('is_active', '1') ? 'checked' : '' }}>
                        </label>
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-sm text-content-secondary">Featured</span>
                            <input type="checkbox" name="is_featured" value="1" class="w-4 h-4 accent-green-500" {{ old('is_featured') ? 'checked' : '' }}>
                        </label>
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-sm text-content-secondary">Organic</span>
                            <input type="checkbox" name="is_organic" value="1" class="w-4 h-4 accent-green-500" {{ old('is_organic') ? 'checked' : '' }}>
                        </label>
                    </div>
                </div>

                {{-- Category + Unit --}}
                <div class="card p-5">
                    <h3 class="font-semibold text-content-primary mb-4">Category</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="label">Category <span class="text-red-400">*</span></label>
                            <select name="category_id" class="input @error('category_id') border-red-500 @enderror" required>
                                <option value="">Select...</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="label">Unit (e.g. per kg, per tray)</label>
                            <input type="text" name="unit" value="{{ old('unit', 'per unit') }}" class="input text-sm">
                        </div>
                        <div>
                            <label class="label">SKU</label>
                            <input type="text" name="sku" value="{{ old('sku') }}" class="input text-sm font-mono">
                        </div>
                        <div>
                            <label class="label">Tags <span class="text-content-muted font-normal">(comma separated)</span></label>
                            <input type="text" name="tags_input" id="tags-input" value="{{ old('tags_input') }}"
                                   placeholder="fresh, organic, local" class="input text-sm">
                        </div>
                        <div>
                            <label class="label">Weight (kg)</label>
                            <input type="number" step="0.01" name="weight" value="{{ old('weight') }}" class="input text-sm">
                        </div>
                    </div>
                </div>

                {{-- Pricing --}}
                <div class="card p-5">
                    <h3 class="font-semibold text-content-primary mb-4">Pricing</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="label">Price (₦) <span class="text-red-400">*</span></label>
                            <input type="number" step="0.01" name="price" value="{{ old('price') }}"
                                   class="input @error('price') border-red-500 @enderror" required>
                            @error('price')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="label">Compare Price (₦)</label>
                            <input type="number" step="0.01" name="compare_price" value="{{ old('compare_price') }}" class="input">
                            <p class="text-xs text-content-muted mt-1">Used to show crossed-out "was" price.</p>
                        </div>
                        <div>
                            <label class="label">Wholesale Price (₦)</label>
                            <input type="number" step="0.01" name="wholesale_price" value="{{ old('wholesale_price') }}" class="input">
                        </div>
                    </div>
                </div>

                {{-- Stock --}}
                <div class="card p-5">
                    <h3 class="font-semibold text-content-primary mb-4">Inventory</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="label">Stock Quantity <span class="text-red-400">*</span></label>
                            <input type="number" name="stock" value="{{ old('stock', 0) }}"
                                   class="input @error('stock') border-red-500 @enderror" required>
                            @error('stock')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="label">Low Stock Threshold</label>
                            <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', 5) }}" class="input">
                        </div>
                    </div>
                </div>

                {{-- SEO --}}
                <div class="card p-5">
                    <h3 class="font-semibold text-content-primary mb-4">SEO</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="label">Meta Title</label>
                            <input type="text" name="meta_title" id="meta-title" value="{{ old('meta_title') }}" class="input text-sm">
                        </div>
                        <div>
                            <label class="label">Meta Description</label>
                            <textarea name="meta_description" id="meta-description" rows="2" class="input resize-none text-sm">{{ old('meta_description') }}</textarea>
                        </div>
                    </div>
                </div>

                <label class="flex items-center gap-3 p-3 rounded-xl bg-surface-elevated border border-surface-border cursor-pointer hover:border-brand-green/40 transition-colors">
                    <input type="checkbox" name="notify_subscribers" value="1" checked
                           class="w-4 h-4 rounded accent-brand-green">
                    <div>
                        <p class="text-sm font-medium text-content-primary">Notify subscribers</p>
                        <p class="text-xs text-content-muted">Send a new product alert email to customers &amp; newsletter subscribers</p>
                    </div>
                </label>

                <button type="submit" class="btn-primary w-full py-3">Create Product</button>
            </div>
        </div>
    </form>
</div>

<script>
function productForm() {
    return {
        slug: '',
        previews: [],
        aiLoading: false,
        aiError: '',
        aiSuccess: false,

        previewImages(e) {
            this.previews = [];
            Array.from(e.target.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = (ev) => this.previews.push(ev.target.result);
                reader.readAsDataURL(file);
            });
        },

        removePreview(idx) {
            this.previews.splice(idx, 1);
        },

        async generateAllContent() {
            const name = document.getElementById('product-name')?.value?.trim();
            const categorySelect = document.querySelector('select[name="category_id"]');
            const categoryText = categorySelect?.options[categorySelect.selectedIndex]?.text || '';

            if (!name) {
                this.aiError = 'Please enter a product name first.';
                return;
            }
            if (!categoryText || categoryText === 'Select...') {
                this.aiError = 'Please select a category first.';
                return;
            }

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
                    body: JSON.stringify({
                        product_name: name,
                        category: categoryText,
                    }),
                });

                if (!res.ok) throw new Error('API error ' + res.status);
                const data = await res.json();

                // Populate text fields
                const set = (id, value) => { const el = document.getElementById(id); if (el && value) el.value = value; };
                set('short-description', data.short_description);
                set('description', data.description);
                set('meta-title', data.meta_title);
                set('meta-description', data.meta_description);
                set('tags-input', data.tags);

                // Populate nutrition facts
                if (data.nutrition_facts && typeof data.nutrition_facts === 'object') {
                    ['calories','protein','fat','carbohydrates','fiber','sugar','sodium'].forEach(n => {
                        const el = document.getElementById('nutrition-' + n);
                        if (el && data.nutrition_facts[n]) el.value = data.nutrition_facts[n];
                    });
                }

                this.aiSuccess = true;
            } catch (e) {
                this.aiError = 'Generation failed: ' + e.message + '. Try again.';
            }

            this.aiLoading = false;
        },
    }
}
</script>
@endsection
