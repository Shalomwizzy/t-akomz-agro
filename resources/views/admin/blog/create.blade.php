@extends('layouts.admin')

@section('page-title', 'New Blog Post')

@push('head')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
@endpush

@section('content')
<div class="max-w-4xl" x-data="{ slug: '', published: false }">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.blog.index') }}" class="text-content-muted hover:text-brand-green transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
    </div>

    <form id="create-form" action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main --}}
            <div class="lg:col-span-2 space-y-5">
                <div class="card p-5">
                    <div class="space-y-4">
                        <div>
                            <label class="label">Post Title <span class="text-red-400">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}"
                                   class="input @error('title') border-red-500 @enderror text-lg" required
                                   @input="slug = $event.target.value.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/(^-|-$)/g,'')">
                            @error('title')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="label">Slug</label>
                            <input type="text" name="slug" :value="slug || '{{ old('slug') }}'" class="input font-mono text-sm">
                        </div>
                        <div>
                            <label class="label">Excerpt <span class="text-red-400">*</span></label>
                            <textarea name="excerpt" rows="2" class="input resize-none text-sm" required
                                      placeholder="Short description for blog listing...">{{ old('excerpt') }}</textarea>
                            @error('excerpt')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="label">Content <span class="text-red-400">*</span></label>
                            <textarea name="content" id="content-input" class="sr-only">{{ old('content') }}</textarea>
                            <div id="quill-editor" class="bg-[#1A1A1A] rounded-xl border border-surface-border min-h-[420px] text-content-primary"></div>
                            @error('content')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-5">
                <div class="card p-5">
                    <h3 class="font-semibold text-content-primary mb-4">Publishing</h3>
                    <div class="space-y-3">
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-sm text-content-secondary">Publish Now</span>
                            <input type="checkbox" name="is_published" value="1" x-model="published" class="w-4 h-4 accent-green-500" {{ old('is_published') ? 'checked' : '' }}>
                        </label>
                        <div x-show="!published">
                            <label class="label">Scheduled Date</label>
                            <input type="datetime-local" name="published_at" value="{{ old('published_at') }}" class="input text-sm">
                        </div>
                    </div>
                </div>

                <div class="card p-5">
                    <h3 class="font-semibold text-content-primary mb-4">Post Details</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="label">Author <span class="text-red-400">*</span></label>
                            <input type="text" name="author_name" value="{{ old('author_name', auth()->user()->name) }}"
                                   class="input text-sm" required>
                        </div>
                        <div>
                            <label class="label">Category</label>
                            <input type="text" name="category" value="{{ old('category') }}"
                                   placeholder="e.g. Farming Tips, News" class="input text-sm">
                        </div>
                        <div>
                            <label class="label">Tags (comma separated)</label>
                            <input type="text" name="tags" value="{{ old('tags') }}"
                                   placeholder="poultry, organic, farming" class="input text-sm">
                        </div>
                    </div>
                </div>

                <div class="card p-5">
                    <h3 class="font-semibold text-content-primary mb-4">Featured Image</h3>
                    <div class="border-2 border-dashed border-surface-border rounded-xl p-5 text-center cursor-pointer hover:border-brand-green/40 transition-colors"
                         onclick="document.getElementById('featured-img').click()">
                        <svg class="w-8 h-8 text-content-muted mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-sm text-content-muted" id="img-label">Click to upload</p>
                        <p class="text-xs text-content-muted mt-0.5">JPG, PNG, WebP up to 4MB</p>
                    </div>
                    <input type="file" id="featured-img" name="cover_image" accept="image/*" class="sr-only"
                           onchange="document.getElementById('img-label').textContent = this.files[0]?.name || 'Click to upload'">
                </div>

                <div class="card p-5">
                    <h3 class="font-semibold text-content-primary mb-4">SEO</h3>
                    <div class="space-y-3">
                        <div><label class="label">Meta Title</label><input type="text" name="meta_title" value="{{ old('meta_title') }}" class="input text-sm"></div>
                        <div><label class="label">Meta Description</label><textarea name="meta_description" rows="2" class="input resize-none text-sm">{{ old('meta_description') }}</textarea></div>
                    </div>
                </div>

                <button type="submit" form="create-form" class="btn-primary w-full py-3">
                    <span x-text="published ? 'Publish Post' : 'Save as Draft'"></span>
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.7/quill.js"></script>
<script>
const quill = new Quill('#quill-editor', {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ header: [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            ['blockquote', 'code-block'],
            [{ list: 'ordered' }, { list: 'bullet' }],
            ['link', 'image'],
            ['clean'],
        ],
    },
    placeholder: 'Write your blog post here...',
});

// Restore old content on validation failure
const existing = document.getElementById('content-input').value;
if (existing) quill.root.innerHTML = existing;

document.getElementById('create-form').addEventListener('submit', function () {
    document.getElementById('content-input').value = quill.root.innerHTML;
});

const style = document.createElement('style');
style.textContent = `
    .ql-toolbar { background: #1A1A1A; border-color: #2A2A2A !important; border-radius: 12px 12px 0 0; }
    .ql-container { border-color: #2A2A2A !important; border-radius: 0 0 12px 12px; font-size: 15px; }
    .ql-editor { color: #F5F5F5; min-height: 380px; }
    .ql-editor.ql-blank::before { color: #666; font-style: normal; }
    .ql-toolbar .ql-stroke { stroke: #CFCFCF; }
    .ql-toolbar .ql-fill { fill: #CFCFCF; }
    .ql-toolbar button:hover .ql-stroke, .ql-toolbar button.ql-active .ql-stroke { stroke: #B8F397; }
    .ql-toolbar button:hover .ql-fill, .ql-toolbar button.ql-active .ql-fill { fill: #B8F397; }
    .ql-toolbar .ql-picker-label { color: #CFCFCF; }
    .ql-toolbar .ql-picker-options { background: #1A1A1A; border-color: #2A2A2A; }
    .ql-toolbar .ql-picker-item { color: #CFCFCF; }
    #quill-editor { border: none; }
`;
document.head.appendChild(style);
</script>
@endpush
@endsection
