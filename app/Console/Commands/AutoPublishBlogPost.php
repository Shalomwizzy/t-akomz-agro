<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use App\Services\AiAssistantService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AutoPublishBlogPost extends Command
{
    protected $signature   = 'blog:auto-publish
                              {--topic= : Topic for the blog post}
                              {--category=Farming Tips : Category name}';
    protected $description = 'AI-generate and auto-publish a blog post with a Pexels cover image';

    private array $defaultTopics = [
        'Modern poultry farming techniques in Nigeria',
        'How to start a profitable egg production farm',
        'Organic farming practices for Nigerian smallholders',
        'Goat farming business guide for beginners',
        'Maize farming: best practices and yields',
        'Cassava processing and value addition in Nigeria',
        'Sustainable livestock management tips',
        'Farm-to-table: why fresh produce matters',
        'Irrigation systems for dry season farming in Nigeria',
        'How to control pests organically on your farm',
    ];

    public function handle(AiAssistantService $ai): int
    {
        $topic    = $this->option('topic') ?: $this->defaultTopics[array_rand($this->defaultTopics)];
        $category = $this->option('category');

        $this->info("Generating blog post: \"{$topic}\"");

        // 1. Generate content
        $result = $ai->generateBlogPost($topic, $category);

        if (empty($result['content'])) {
            $this->error('AI returned empty content. Aborting.');
            return self::FAILURE;
        }

        $wordCount = str_word_count(strip_tags($result['content']));
        $this->line("Word count: {$wordCount}");

        if ($wordCount < 400) {
            $this->warn('Content too short (< 400 words). Aborting.');
            return self::FAILURE;
        }

        $title = $result['title'] ?? $topic;
        $slug  = Str::slug($title);

        if (BlogPost::where('slug', $slug)->exists()) {
            $slug .= '-' . now()->format('YmdHis');
        }

        // 2. Fetch Pexels image
        $coverImage = $this->fetchPexelsImage($topic);
        if ($coverImage) {
            $this->info("Cover image saved: {$coverImage}");
        } else {
            $this->warn('No cover image fetched — publishing without one.');
        }

        // 3. Create post
        $post = BlogPost::create([
            'title'        => $title,
            'slug'         => $slug,
            'excerpt'      => $result['excerpt'] ?? Str::limit(strip_tags($result['content']), 160),
            'content'      => $result['content'],
            'cover_image'  => $coverImage,
            'author_name'  => 'T-Akomz Farm Team',
            'category'     => $category,
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->info("Published: [{$post->id}] {$post->title}");
        $this->line("URL: " . route('blog.show', $post->slug));

        return self::SUCCESS;
    }

    private function fetchPexelsImage(string $query): ?string
    {
        $apiKey = config('services.pexels.key');
        if (!$apiKey) {
            $this->warn('PEXELS_API_KEY not set — skipping image fetch.');
            return null;
        }

        try {
            $response = Http::withHeaders(['Authorization' => $apiKey])
                ->timeout(10)
                ->get('https://api.pexels.com/v1/search', [
                    'query'       => $query . ' farm agriculture',
                    'per_page'    => 5,
                    'orientation' => 'landscape',
                ]);

            $photos = $response->successful() ? $response->json('photos', []) : [];

            if (empty($photos)) {
                $response = Http::withHeaders(['Authorization' => $apiKey])
                    ->timeout(10)
                    ->get('https://api.pexels.com/v1/search', [
                        'query'       => 'farm agriculture Nigeria',
                        'per_page'    => 5,
                        'orientation' => 'landscape',
                    ]);
                $photos = $response->json('photos', []);
            }

            if (empty($photos)) {
                return null;
            }

            $photo    = $photos[array_rand($photos)];
            $imageUrl = $photo['src']['large2x'] ?? $photo['src']['large'] ?? null;

            if (!$imageUrl) {
                return null;
            }

            $imageData = Http::timeout(15)->get($imageUrl);
            if (!$imageData->successful()) {
                return null;
            }

            $filename = 'blog/pexels-' . Str::random(12) . '.jpg';
            Storage::disk('public')->put($filename, $imageData->body());

            return $filename;
        } catch (\Throwable $e) {
            Log::error('Pexels image fetch failed', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
