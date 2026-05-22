@extends('layouts.admin')

@section('page-title', 'Blog Posts')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div></div>
    <a href="{{ route('admin.blog.create') }}" class="btn-primary py-2.5 px-5 text-sm">+ New Post</a>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-header text-left">Title</th>
                    <th class="table-header text-left">Category</th>
                    <th class="table-header text-left">Status</th>
                    <th class="table-header text-left">Date</th>
                    <th class="table-header text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                <tr class="hover:bg-surface-elevated/50 transition-colors">
                    <td class="table-cell">
                        <div class="flex items-center gap-3">
                            @if($post->cover_image)
                            <img src="{{ asset('storage/' . $post->cover_image) }}" alt="" class="w-10 h-10 object-cover rounded-lg flex-shrink-0">
                            @else
                            <div class="w-10 h-10 bg-surface-elevated rounded-lg flex-shrink-0"></div>
                            @endif
                            <p class="text-sm text-content-primary font-medium line-clamp-1">{{ $post->title }}</p>
                        </div>
                    </td>
                    <td class="table-cell"><span class="text-sm text-content-secondary">{{ $post->category ?? '—' }}</span></td>
                    <td class="table-cell">
                        @if($post->is_published)
                        <span class="badge bg-brand-green/15 text-brand-green text-xs">Published</span>
                        @else
                        <span class="badge bg-surface-elevated text-content-muted text-xs">Draft</span>
                        @endif
                    </td>
                    <td class="table-cell"><span class="text-xs text-content-muted">{{ $post->published_at?->format('d M Y') ?? $post->created_at->format('d M Y') }}</span></td>
                    <td class="table-cell">
                        <div class="flex gap-3">
                            <a href="{{ route('admin.blog.edit', $post) }}" class="text-xs text-brand-green hover:underline">Edit</a>
                            <a href="{{ route('blog.show', $post->slug) }}" target="_blank" rel="noopener noreferrer"  class="text-xs text-content-muted hover:underline">View</a>
                            <form action="{{ route('admin.blog.destroy', $post) }}" method="POST"
                                  onsubmit="return confirm('Delete this post?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:underline">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-12 text-center text-content-muted text-sm">No blog posts yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">{{ $posts->links() }}</div>
@endsection
