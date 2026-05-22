<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\HtmlSanitizer;
use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::latest()->paginate(20);
        return view('admin.blog.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.blog.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatePost($request);

        $data['slug'] = Str::slug($data['title']);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('blog', 'public');
        }

        if ($data['is_published'] && !$data['published_at']) {
            $data['published_at'] = now();
        }

        BlogPost::create($data);

        return redirect()->route('admin.blog.index')->with('success', 'Blog post created.');
    }

    public function edit(BlogPost $blog)
    {
        $post = $blog;
        return view('admin.blog.edit', compact('post'));
    }

    public function update(Request $request, BlogPost $blog)
    {
        $data = $this->validatePost($request);

        if ($request->hasFile('cover_image')) {
            if ($blog->cover_image) {
                Storage::disk('public')->delete($blog->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('blog', 'public');
        }

        if ($data['is_published'] && !$blog->published_at) {
            $data['published_at'] = now();
        }

        $blog->update($data);

        return redirect()->route('admin.blog.index')->with('success', 'Post updated.');
    }

    public function destroy(BlogPost $blog)
    {
        if ($blog->cover_image) {
            Storage::disk('public')->delete($blog->cover_image);
        }
        $blog->delete();
        return back()->with('success', 'Post deleted.');
    }

    private function validatePost(Request $request): array
    {
        $data = $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'excerpt'          => ['required', 'string', 'max:500'],
            'content'          => ['required', 'string'],
            'author_name'      => ['required', 'string', 'max:100'],
            'category'         => ['nullable', 'string', 'max:100'],
            'tags'             => ['nullable', 'string'],
            'cover_image'      => ['nullable', 'image', 'max:3072'],
            'is_published'     => ['boolean'],
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:300'],
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $data['tags']         = $request->filled('tags') ? array_map('trim', explode(',', $request->tags)) : [];
        $data['published_at'] = $request->filled('published_at') ? $request->published_at : null;
        $data['content']      = HtmlSanitizer::clean($data['content']);

        return $data;
    }
}
