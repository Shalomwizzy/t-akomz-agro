<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Services\AiAssistantService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AiController extends Controller
{
    public function __construct(private AiAssistantService $ai) {}

    public function index()
    {
        return view('admin.ai.index');
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'history' => ['nullable', 'array', 'max:20'],
        ]);

        $reply = $this->ai->adminChat($request->message, $request->input('history', []));

        return response()->json(['reply' => $reply]);
    }

    public function productContent(Request $request)
    {
        $request->validate([
            'product_name' => ['required', 'string', 'max:200'],
            'category'     => ['required', 'string', 'max:100'],
            'details'      => ['nullable', 'string', 'max:500'],
        ]);

        $content = $this->ai->generateProductContent(
            $request->product_name,
            $request->category,
            $request->details
        );

        return response()->json($content);
    }

    public function productDescription(Request $request)
    {
        $request->validate([
            'product_name' => ['required', 'string', 'max:200'],
            'category'     => ['required', 'string', 'max:100'],
            'details'      => ['nullable', 'string', 'max:500'],
        ]);

        $description = $this->ai->generateProductDescription(
            $request->product_name,
            $request->category,
            $request->details
        );

        return response()->json(['description' => $description]);
    }

    public function businessPlan(Request $request)
    {
        $request->validate([
            'section' => ['required', 'string', 'max:200'],
            'context' => ['nullable', 'string', 'max:1000'],
        ]);

        $content = $this->ai->generateBusinessPlan($request->section, $request->context);

        return response()->json(['content' => $content]);
    }

    public function blogPost(Request $request)
    {
        $request->validate([
            'topic'    => ['required', 'string', 'max:200'],
            'category' => ['nullable', 'string', 'max:100'],
        ]);

        $result = $this->ai->generateBlogPost(
            $request->topic,
            $request->input('category', 'Farming Tips')
        );

        return response()->json($result);
    }

    public function autoPublish(Request $request)
    {
        $request->validate([
            'topic'    => ['required', 'string', 'max:200'],
            'category' => ['nullable', 'string', 'max:100'],
        ]);

        $topic    = $request->topic;
        $category = $request->input('category', 'Farming Tips');

        // 1. Generate content via AI
        $result = $this->ai->generateBlogPost($topic, $category);

        if (empty($result['content']) || str_word_count(strip_tags($result['content'])) < 300) {
            return response()->json(['error' => 'AI did not return enough content. Try again.'], 422);
        }

        $title = $result['title'] ?? $topic;
        $slug  = Str::slug($title);

        // Ensure unique slug
        if (BlogPost::where('slug', $slug)->exists()) {
            $slug .= '-' . now()->format('YmdHis');
        }

        // 2. Fetch cover image from Pexels
        $coverImage = $this->fetchPexelsImage($topic);

        // 3. Save blog post
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

        return response()->json([
            'success' => true,
            'post'    => [
                'id'       => $post->id,
                'title'    => $post->title,
                'slug'     => $post->slug,
                'category' => $post->category,
                'url'      => route('blog.show', $post->slug),
            ],
        ]);
    }

    private function fetchPexelsImage(string $query): ?string
    {
        $apiKey = config('services.pexels.key');
        if (!$apiKey) {
            return null;
        }

        try {
            $searchTerm = $query . ' agriculture farm Nigeria';
            $response   = Http::withHeaders(['Authorization' => $apiKey])
                ->timeout(10)
                ->get('https://api.pexels.com/v1/search', [
                    'query'       => $searchTerm,
                    'per_page'    => 5,
                    'orientation' => 'landscape',
                ]);

            if (!$response->successful()) {
                return null;
            }

            $photos = $response->json('photos', []);
            if (empty($photos)) {
                // Fallback to generic farm search
                $response = Http::withHeaders(['Authorization' => $apiKey])
                    ->timeout(10)
                    ->get('https://api.pexels.com/v1/search', [
                        'query'       => 'farm agriculture crops',
                        'per_page'    => 5,
                        'orientation' => 'landscape',
                    ]);
                $photos = $response->json('photos', []);
            }

            if (empty($photos)) {
                return null;
            }

            // Pick a random photo from results
            $photo   = $photos[array_rand($photos)];
            $imageUrl = $photo['src']['large2x'] ?? $photo['src']['large'] ?? null;

            if (!$imageUrl) {
                return null;
            }

            // Download and store locally
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
