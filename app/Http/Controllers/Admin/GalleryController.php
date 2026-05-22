<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $images = GalleryImage::orderBy('sort_order')->orderByDesc('created_at')->paginate(24);
        return view('admin.gallery.index', compact('images'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'images'          => ['required', 'array', 'min:1'],
            'images.*'        => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'category'        => ['nullable', 'string', 'max:50'],
        ]);

        foreach ($request->file('images') as $file) {
            $path = $file->store('gallery', 'public');
            GalleryImage::create([
                'path'       => $path,
                'caption'    => null,
                'alt_text'   => 'T-Akomz Farm',
                'category'   => $request->input('category', 'general'),
                'sort_order' => GalleryImage::max('sort_order') + 1,
            ]);
        }

        return back()->with('success', count($request->file('images')) . ' image(s) uploaded successfully.');
    }

    public function update(Request $request, GalleryImage $gallery)
    {
        $request->validate([
            'caption'    => ['nullable', 'string', 'max:255'],
            'alt_text'   => ['nullable', 'string', 'max:255'],
            'category'   => ['nullable', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active'  => ['boolean'],
        ]);

        $gallery->update($request->only('caption', 'alt_text', 'category', 'sort_order', 'is_active'));

        return back()->with('success', 'Image updated.');
    }

    public function destroy(GalleryImage $gallery)
    {
        Storage::disk('public')->delete($gallery->path);
        $gallery->delete();

        return back()->with('success', 'Image deleted.');
    }

    public function toggle(GalleryImage $gallery)
    {
        $gallery->update(['is_active' => !$gallery->is_active]);
        return back()->with('success', 'Visibility updated.');
    }
}
