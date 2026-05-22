<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature   = 'sitemap:generate';
    protected $description = 'Generate the XML sitemap';

    public function handle(): int
    {
        $sitemap = Sitemap::create();

        // Static pages
        $statics = [
            ['/', 1.0, 'daily'],
            ['/shop', 0.9, 'hourly'],
            ['/about', 0.6, 'monthly'],
            ['/contact', 0.5, 'monthly'],
            ['/blog', 0.7, 'daily'],
            ['/faq', 0.5, 'monthly'],
            ['/track', 0.4, 'monthly'],
        ];

        foreach ($statics as [$path, $priority, $frequency]) {
            $sitemap->add(
                Url::create(config('app.url') . $path)
                    ->setPriority($priority)
                    ->setChangeFrequency($frequency)
                    ->setLastModificationDate(now())
            );
        }

        // Products — URL structure: /shop/{category-slug}/{product-slug}
        Product::where('is_active', true)->with('category')->cursor()->each(function (Product $product) use ($sitemap) {
            $categorySlug = $product->category?->slug ?? 'products';
            $sitemap->add(
                Url::create(config('app.url') . '/shop/' . $categorySlug . '/' . $product->slug)
                    ->setPriority(0.8)
                    ->setChangeFrequency('weekly')
                    ->setLastModificationDate($product->updated_at)
            );
        });

        // Categories
        Category::where('is_active', true)->cursor()->each(function (Category $category) use ($sitemap) {
            $sitemap->add(
                Url::create(config('app.url') . '/shop/' . $category->slug)
                    ->setPriority(0.7)
                    ->setChangeFrequency('weekly')
                    ->setLastModificationDate($category->updated_at)
            );
        });

        // Blog posts
        BlogPost::published()->cursor()->each(function (BlogPost $post) use ($sitemap) {
            $sitemap->add(
                Url::create(config('app.url') . '/blog/' . $post->slug)
                    ->setPriority(0.6)
                    ->setChangeFrequency('monthly')
                    ->setLastModificationDate($post->updated_at)
            );
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated → public/sitemap.xml (' . $this->countUrls($sitemap) . ' URLs)');

        return self::SUCCESS;
    }

    private function countUrls(Sitemap $sitemap): int
    {
        // Reflection to count tags since Sitemap doesn't expose a count method
        try {
            $ref  = new \ReflectionClass($sitemap);
            $prop = $ref->getProperty('tags');
            $prop->setAccessible(true);
            return count($prop->getValue($sitemap));
        } catch (\Throwable) {
            return 0;
        }
    }
}
