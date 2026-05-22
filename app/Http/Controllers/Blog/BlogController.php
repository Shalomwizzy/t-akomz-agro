<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::published()
            ->orderByDesc('published_at')
            ->paginate(12);

        $categories = BlogPost::published()
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        $featured = BlogPost::published()
            ->orderByDesc('views')
            ->first();

        $seoTitle       = 'Farm News & Insights';
        $seoDescription = 'Read the latest farming tips, agro news, and updates from T-Akomz Agro Estates — Nigeria\'s premium farm estate.';

        return view('blog.index', compact('posts', 'categories', 'featured', 'seoTitle', 'seoDescription'));
    }

    public function show(string $slug)
    {
        $post = BlogPost::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $post->increment('views');

        $related = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->where('category', $post->category)
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        $seoTitle       = $post->title;
        $seoDescription = $post->excerpt ?? strip_tags(substr($post->content ?? '', 0, 160));
        $seoImage       = $post->cover_image ? asset('storage/' . $post->cover_image) : null;
        $seoType        = 'article';

        return view('blog.show', compact('post', 'related', 'seoTitle', 'seoDescription', 'seoImage', 'seoType'));
    }
}
