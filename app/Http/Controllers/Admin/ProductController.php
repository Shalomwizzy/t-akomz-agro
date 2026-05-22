<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\HtmlSanitizer;
use App\Http\Controllers\Controller;
use App\Exports\ProductsExport;
use App\Mail\LowStockAlert;
use App\Mail\NewProductAlert;
use App\Models\Category;
use App\Models\NewsletterSubscriber;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\SiteSetting;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category', 'images')->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $products   = $query->paginate(20)->withQueryString();
        $categories = Category::active()->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validateProduct($request);
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $product = Product::create($data);

        $this->handleImages($request, $product);

        if ($request->boolean('notify_subscribers', true)) {
            $this->notifyNewProduct($product);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $product->load('images');
        $categories = Category::active()->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateProduct($request, $product->id);

        // Keep existing slug if form didn't change it
        if (empty($data['slug'])) {
            unset($data['slug']);
        }

        $oldStock = $product->stock;
        $product->update($data);
        $product->refresh();

        $this->handleImages($request, $product);

        // Fire low stock alert if stock just dropped to or below threshold
        $threshold = $product->low_stock_threshold ?? 10;
        if ($product->stock <= $threshold && $product->stock < $oldStock) {
            try {
                $adminEmail = \App\Models\SiteSetting::adminEmail();
                if ($adminEmail) {
                    Mail::to($adminEmail)->send(new LowStockAlert($product));
                }
            } catch (\Throwable $e) {
                Log::error('Low stock alert email failed', ['product' => $product->id, 'error' => $e->getMessage()]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->url);
        }
        $product->delete();

        return back()->with('success', 'Product deleted.');
    }

    public function toggleActive(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        return back()->with('success', 'Product status updated.');
    }

    public function toggleFeatured(Product $product)
    {
        $product->update(['is_featured' => !$product->is_featured]);
        return back()->with('success', 'Product featured status updated.');
    }

    public function deleteImage(Product $product, ProductImage $image)
    {
        abort_unless($image->product_id === $product->id, 404);
        Storage::disk('public')->delete($image->url);
        $image->delete();
        return back()->with('success', 'Image deleted.');
    }

    private function validateProduct(Request $request, ?int $excludeId = null): array
    {
        $validated = $request->validate([
            'category_id'         => ['required', 'exists:categories,id'],
            'name'                => ['required', 'string', 'max:200'],
            'slug'                => ['nullable', 'string', 'max:220'],
            'short_description'   => ['nullable', 'string', 'max:500'],
            'description'         => ['required', 'string'],
            'price'               => ['required', 'numeric', 'min:0'],
            'compare_price'       => ['nullable', 'numeric', 'min:0'],
            'wholesale_price'     => ['nullable', 'numeric', 'min:0'],
            'unit'                => ['required', 'string', 'max:50'],
            'sku'                 => ['nullable', 'string', 'max:100', 'unique:products,sku,' . $excludeId],
            'stock'               => ['required', 'integer', 'min:0'],
            'low_stock_threshold' => ['nullable', 'integer', 'min:0'],
            'weight'              => ['nullable', 'numeric', 'min:0'],
            'meta_title'          => ['nullable', 'string', 'max:255'],
            'meta_description'    => ['nullable', 'string', 'max:300'],
            'tags_input'          => ['nullable', 'string'],
            'nutrition_facts'     => ['nullable', 'array'],
        ]);

        $validated['is_active']      = $request->boolean('is_active');
        $validated['is_featured']    = $request->boolean('is_featured');
        $validated['is_organic']     = $request->boolean('is_organic');
        $validated['description']    = HtmlSanitizer::clean($validated['description']);

        // Convert tags_input (comma string) → JSON array
        $tagsInput = $request->input('tags_input', '');
        $validated['tags'] = $tagsInput
            ? array_values(array_filter(array_map('trim', explode(',', $tagsInput))))
            : [];
        unset($validated['tags_input']);

        // Nutrition facts: filter out empty values
        if (!empty($validated['nutrition_facts'])) {
            $validated['nutrition_facts'] = array_filter($validated['nutrition_facts'], fn($v) => $v !== '' && $v !== null);
        }

        return $validated;
    }

    private function handleImages(Request $request, Product $product): void
    {
        if (!$request->hasFile('images')) {
            return;
        }

        $manager   = new ImageManager(new Driver());
        $watermark = SiteSetting::get('image_watermark', '0') === '1';
        $wmText    = SiteSetting::get('watermark_text', 'T-AKOMZ AGRO');
        $dir       = storage_path('app/public/products');

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        foreach ($request->file('images') as $index => $file) {
            $filename    = 'products/' . Str::uuid() . '.webp';
            $storagePath = storage_path('app/public/' . $filename);

            $image = $manager->decodePath($file->getRealPath());
            $image->scaleDown(width: 1200, height: 1200);

            if ($watermark) {
                try {
                    $image->text(
                        $wmText,
                        (int) ($image->width() / 2),
                        $image->height() - 20,
                        function ($font) {
                            $font->size(26);
                            $font->color('rgba(255,255,255,0.55)');
                            $font->align('center');
                        }
                    );
                } catch (\Throwable) {
                    // Skip watermark if font rendering fails
                }
            }

            $image->encode(new \Intervention\Image\Encoders\WebpEncoder(quality: 82))
                  ->save($storagePath);

            ProductImage::create([
                'product_id' => $product->id,
                'url'        => $filename,
                'alt_text'   => $product->name,
                'is_primary' => $index === 0 && !$product->primaryImage()->exists(),
                'sort_order' => $product->images()->count() + $index,
            ]);
        }
    }

    public function export(Request $request)
    {
        $filename = 'products-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(
            new ProductsExport(
                category: $request->category ?: null,
                active:   $request->filled('status') ? ($request->status === 'active') : null,
            ),
            $filename
        );
    }

    private function notifyNewProduct(Product $product): void
    {
        try {
            $product->load('category');

            // Newsletter subscribers
            $subscriberEmails = NewsletterSubscriber::where('is_active', true)
                ->pluck('email')
                ->toArray();

            // Registered customers (who are not already in the subscriber list)
            $customerEmails = User::whereHas('roles', fn($q) => $q->where('name', 'CUSTOMER'))
                ->orWhereDoesntHave('roles')
                ->whereNotNull('email')
                ->pluck('email')
                ->toArray();

            $recipients = array_unique(array_merge($subscriberEmails, $customerEmails));

            foreach (array_chunk($recipients, 50) as $chunk) {
                foreach ($chunk as $email) {
                    Mail::to($email)->send(new NewProductAlert($product));
                }
            }
        } catch (\Throwable $e) {
            Log::error('New product alert failed', ['product' => $product->id, 'error' => $e->getMessage()]);
        }
    }
}
