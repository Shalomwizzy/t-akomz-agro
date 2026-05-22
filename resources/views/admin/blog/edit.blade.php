@extends('layouts.admin')

@section('page-title', 'Edit: ' . Str::limit($post->title, 40))

@push('head')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
@endpush

@section('content')
<div class="max-w-4xl" x-data="{ slug: '{{ $post->slug }}', published: {{ $post->is_published ? 'true' : 'false' }} }">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.blog.index') }}" class="text-content-muted hover:text-brand-green transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div class="flex gap-2 ml-auto">
            <a href="{{ route('blog.show', $post->slug) }}" target="_blank" rel="noopener noreferrer"  class="btn-ghost py-2 px-4 text-xs">View Post ↗</a>
        </div>
    </div>

    {{-- UPDATE FORM --}}
    <form id="update-form" action="{{ route('admin.blog.update', $post) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-5">
                <div class="card p-5 space-y-4">
                    <div>
                        <label class="label">Post Title</label>
                        <input type="text" name="title" value="{{ old('title', $post->title) }}"
                               class="input text-lg" required
                               @input="slug = $event.target.value.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/(^-|-$)/g,'')">
                    </div>
                    <div>
                        <label class="label">Slug</label>
                        <input type="text" name="slug" :value="slug" class="input font-mono text-sm">
                    </div>
                    <div>
                        <label class="label">Excerpt</label>
                        <textarea name="excerpt" rows="2" class="input resize-none text-sm">{{ old('excerpt', $post->excerpt) }}</textarea>
                    </div>
                    <div>
                        <label class="label">Content <span class="text-red-400">*</span></label>
                        {{-- Hidden textarea that Quill syncs into on submit --}}
                        <textarea name="content" id="content-input" class="sr-only">{{ old('content', $post->content) }}</textarea>
                        <div id="quill-editor" class="bg-[#1A1A1A] rounded-xl border border-surface-border min-h-[420px] text-content-primary"></div>
                        @error('content')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="space-y-5">
                <div class="card p-5">
                    <h3 class="font-semibold text-content-primary mb-4">Publishing</h3>
                    <div class="space-y-3">
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-sm text-content-secondary">Published</span>
                            <input type="checkbox" name="is_published" value="1" x-model="published" class="w-4 h-4 accent-green-500" {{ old('is_published', $post->is_published) ? 'checked' : '' }}>
                        </label>
                        <div>
                            <label class="label">Publish Date</label>
                            <input type="datetime-local" name="published_at"
                                   value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}"
                                   class="input text-sm">
                        </div>
                    </div>
                </div>

                <div class="card p-5">
                    <h3 class="font-semibold text-content-primary mb-4">Details</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="label">Author</label>
                            <input type="text" name="author_name" value="{{ old('author_name', $post->author_name) }}" class="input text-sm">
                        </div>
                        <div>
                            <label class="label">Category</label>
                            <input type="text" name="category" value="{{ old('category', $post->category) }}" class="input text-sm">
                        </div>
                        <div>
                            <label class="label">Tags (comma separated)</label>
                            <input type="text" name="tags" value="{{ old('tags', implode(', ', $post->tags ?? [])) }}" class="input text-sm">
                        </div>
                    </div>
                </div>

                <div class="card p-5">
                    <h3 class="font-semibold text-content-primary mb-3">Featured Image</h3>
                    @if($post->cover_image)
                    <img src="{{ asset('storage/' . $post->cover_image) }}" alt="" class="w-full h-32 object-cover rounded-xl mb-3">
                    @endif
                    <div class="border-2 border-dashed border-surface-border rounded-xl p-4 text-center cursor-pointer hover:border-brand-green/40 transition-colors"
                         onclick="document.getElementById('fi').click()">
                        <p class="text-xs text-content-muted" id="fi-label">{{ $post->cover_image ? 'Replace image' : 'Upload image' }}</p>
                    </div>
                    <input type="file" id="fi" name="cover_image" accept="image/*" class="sr-only"
                           onchange="document.getElementById('fi-label').textContent = this.files[0]?.name || 'Replace image'">
                </div>

                <div class="card p-5">
                    <h3 class="font-semibold text-content-primary mb-4">SEO</h3>
                    <div class="space-y-3">
                        <div><label class="label">Meta Title</label><input type="text" name="meta_title" value="{{ old('meta_title', $post->meta_title) }}" class="input text-sm"></div>
                        <div><label class="label">Meta Description</label><textarea name="meta_description" rows="2" class="input resize-none text-sm">{{ old('meta_description', $post->meta_description) }}</textarea></div>
                    </div>
                </div>

                <button type="submit" form="update-form" class="btn-primary w-full py-3 text-sm">Save Changes</button>
            </div>
        </div>
    </form>

    {{-- DELETE FORM — outside update form to avoid nested form bug --}}
    <form action="{{ route('admin.blog.destroy', $post) }}" method="POST" class="mt-4"
          onsubmit="return confirm('Delete this post permanently?')">
        @csrf @method('DELETE')
        <button type="submit" class="btn-danger w-full py-2.5 text-sm">Delete Post</button>
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

// Load existing content
const existing = document.getElementById('content-input').value;
if (existing) {
    quill.root.innerHTML = existing;
}

// Sync Quill HTML to hidden textarea before form submit
document.getElementById('update-form').addEventListener('submit', function () {
    document.getElementById('content-input').value = quill.root.innerHTML;
});

// Style the Quill toolbar for dark theme
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
