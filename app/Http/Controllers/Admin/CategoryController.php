<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount(['products', 'activeProducts'])->orderBy('sort_order')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::withCount(['products', 'activeProducts'])->orderBy('sort_order')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function edit(Category $category)
    {
        $categories    = Category::withCount(['products', 'activeProducts'])->orderBy('sort_order')->get();
        $editCategory  = $category;
        return view('admin.categories.index', compact('categories', 'editCategory'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
            'sort_order'  => ['integer', 'min:0'],
            'is_active'   => ['boolean'],
            'image'       => ['nullable', 'image', 'max:2048'],
        ]);

        $data['slug']      = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category created.');
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100', 'unique:categories,name,' . $category->id],
            'description' => ['nullable', 'string'],
            'sort_order'  => ['integer', 'min:0'],
            'is_active'   => ['boolean'],
            'image'       => ['nullable', 'image', 'max:2048'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($category->image);
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        return back()->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Cannot delete category with products. Move products first.');
        }
        Storage::disk('public')->delete($category->image);
        $category->delete();
        return back()->with('success', 'Category deleted.');
    }

    public function reorder(Request $request)
    {
        foreach ($request->order as $index => $id) {
            Category::where('id', $id)->update(['sort_order' => $index]);
        }
        return response()->json(['success' => true]);
    }
}
