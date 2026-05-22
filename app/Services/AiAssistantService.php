<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiAssistantService
{
    private string $groqKey;
    private string $groqModel;
    private string $groqUrl;
    private string $geminiKey;
    private string $geminiModel;
    private string $geminiUrl;

    public function __construct()
    {
        $this->groqKey    = config('services.groq.api_key', '');
        $this->groqModel  = config('services.groq.model', 'llama3-70b-8192');
        $this->groqUrl    = config('services.groq.base_url', 'https://api.groq.com/openai/v1');
        $this->geminiKey  = config('services.gemini.api_key', '');
        $this->geminiModel = config('services.gemini.model', 'gemini-1.5-flash');
        $this->geminiUrl  = config('services.gemini.base_url', 'https://generativelanguage.googleapis.com/v1beta');
    }

    // ─── Public Chat ──────────────────────────────────────────────────────────

    /**
     * Handle a public customer chat message.
     * Returns ['reply' => string, 'products' => array|null]
     */
    public function chat(string $message, array $history = []): array
    {
        $systemPrompt = $this->buildPublicSystemPrompt();
        $reply = $this->callGroq($systemPrompt, $message, $history);

        // Check if reply references products and attach them
        $products = $this->extractProductSuggestions($reply, $message);

        return [
            'reply'    => $reply,
            'products' => $products,
        ];
    }

    // ─── Admin Tools ──────────────────────────────────────────────────────────

    /**
     * Generate a product description for admin.
     */
    public function generateProductDescription(string $productName, string $category, ?string $details = null): string
    {
        $prompt = "Write a compelling, SEO-friendly product description for a Nigerian farm product called \"{$productName}\" in the category \"{$category}\".";
        if ($details) {
            $prompt .= " Additional details: {$details}.";
        }
        $prompt .= " The description should be 2-3 paragraphs, highlight freshness, organic quality, and health benefits. Mention T-Akomz Agro Estates as the producer. Write in a professional yet warm tone.";

        return $this->callAdminAi($prompt);
    }

    /**
     * Generate ALL product content at once: description, short description,
     * meta title, meta description, nutrition facts, and suggested tags.
     * Returns an array with all fields populated.
     */
    public function generateProductContent(string $productName, string $category, ?string $details = null): array
    {
        $extraContext = $details ? " Additional details: {$details}." : '';

        $prompt = <<<PROMPT
Generate comprehensive product content for an e-commerce listing on T-Akomz Agro Estates Ltd (a premium Nigerian organic farm).

Product: "{$productName}"
Category: {$category}{$extraContext}

Return ONLY a valid JSON object with exactly these fields:
{
  "short_description": "1-2 sentence teaser (max 160 chars)",
  "description": "Full HTML description: 3 paragraphs with <p> tags, highlight freshness/organic quality/health benefits, mention T-Akomz Agro Estates",
  "meta_title": "SEO meta title (max 60 chars) - product name + key benefit + brand",
  "meta_description": "SEO meta description (max 155 chars) - compelling, includes product name and call to action",
  "tags": "comma-separated tags, 5-8 relevant keywords (e.g. fresh, organic, farm-raised)",
  "nutrition_facts": {
    "calories": "value with unit or empty string",
    "protein": "value with unit or empty string",
    "fat": "value with unit or empty string",
    "carbohydrates": "value with unit or empty string",
    "fiber": "value with unit or empty string",
    "sugar": "value with unit or empty string",
    "sodium": "value with unit or empty string"
  }
}

For nutrition_facts, provide typical values for this type of product (per 100g where applicable). If the product doesn't have meaningful nutritional data (e.g. compost, seedlings), use empty strings.
Do not include any explanation, markdown, or extra text — just the raw JSON.
PROMPT;

        $raw    = $this->callAdminAi($prompt);
        $parsed = json_decode($this->extractJson($raw), true);

        return $parsed ?? [
            'short_description' => '',
            'description'       => $raw,
            'meta_title'        => $productName . ' | T-Akomz Agro Estates',
            'meta_description'  => "Buy fresh {$productName} from T-Akomz Agro Estates. Premium quality, farm-fresh, delivered to your door.",
            'tags'              => strtolower($category) . ', fresh, organic, farm',
            'nutrition_facts'   => [],
        ];
    }

    /**
     * Generate a business plan section or full plan.
     */
    public function generateBusinessPlan(string $section, ?string $context = null): string
    {
        $prompt = "Write a professional business plan section: \"{$section}\" for T-Akomz Agro Estates Ltd, a Nigerian agricultural e-commerce company operating a 50-acre farm producing poultry, eggs, livestock, and organic produce.";
        if ($context) {
            $prompt .= " Additional context: {$context}.";
        }
        $prompt .= " Include realistic Nigerian market data, revenue projections in Naira, and actionable strategies.";

        return $this->callAdminAi($prompt);
    }

    /**
     * Analyze sales data and return insights.
     */
    public function analyzeSalesInsights(array $data): string
    {
        $dataJson = json_encode($data, JSON_PRETTY_PRINT);
        $prompt   = "Analyze the following sales data for T-Akomz Agro Estates and provide 5 actionable business insights with specific recommendations:\n\n{$dataJson}";

        return $this->callAdminAi($prompt);
    }

    /**
     * Generate a blog post.
     */
    public function generateBlogPost(string $topic, string $category = 'Farming Tips'): array
    {
        $prompt = "Write a full blog post for T-Akomz Agro Estates website. Topic: \"{$topic}\". Category: {$category}. Include: a compelling title, a 2-sentence excerpt, and full article content (600-900 words) with subheadings. Format as JSON: {\"title\": \"...\", \"excerpt\": \"...\", \"content\": \"...\"}";

        $raw    = $this->callAdminAi($prompt);
        $parsed = json_decode($this->extractJson($raw), true);

        return $parsed ?? ['title' => $topic, 'excerpt' => '', 'content' => $raw];
    }

    /**
     * Handle a general admin assistant message.
     */
    public function adminChat(string $message, array $history = []): string
    {
        return $this->callAdminAi($message, $history);
    }

    // ─── LLM Callers ─────────────────────────────────────────────────────────

    /**
     * Primary caller for all admin AI tasks.
     * Uses GROQ first (reliable, generous limits), falls back to Gemini.
     */
    private function callAdminAi(string $userMessage, array $history = []): string
    {
        return $this->callGroq($this->buildAdminSystemPrompt(), $userMessage, $history);
    }

    private function callGroq(string $system, string $userMessage, array $history = []): string
    {
        if (empty($this->groqKey)) {
            return $this->fallbackToGemini($system, $userMessage, $history);
        }

        $messages = [['role' => 'system', 'content' => $system]];
        foreach ($history as $h) {
            $messages[] = ['role' => $h['role'], 'content' => $h['content']];
        }
        $messages[] = ['role' => 'user', 'content' => $userMessage];

        try {
            $response = Http::withToken($this->groqKey)
                ->timeout(30)
                ->post("{$this->groqUrl}/chat/completions", [
                    'model'       => $this->groqModel,
                    'messages'    => $messages,
                    'max_tokens'  => 2048,
                    'temperature' => 0.7,
                ]);

            if (!$response->successful()) {
                Log::error('GROQ API error', ['status' => $response->status(), 'body' => $response->body()]);
                return $this->fallbackToGemini($system, $userMessage, $history);
            }

            return trim($response->json('choices.0.message.content', ''));
        } catch (\Throwable $e) {
            Log::error('GROQ call failed', ['error' => $e->getMessage()]);
            return $this->fallbackToGemini($system, $userMessage, $history);
        }
    }

    private function callGemini(string $system, string $userMessage, array $history = []): string
    {
        if (empty($this->geminiKey)) {
            return "AI assistant is not configured yet. Please add your GEMINI_API_KEY to the environment settings.";
        }

        $contents = [];
        foreach ($history as $h) {
            $contents[] = [
                'role'  => $h['role'] === 'assistant' ? 'model' : 'user',
                'parts' => [['text' => $h['content']]],
            ];
        }
        $contents[] = ['role' => 'user', 'parts' => [['text' => $userMessage]]];

        try {
            $response = Http::withQueryParameters(['key' => $this->geminiKey])
                ->timeout(30)
                ->post("{$this->geminiUrl}/models/{$this->geminiModel}:generateContent", [
                    'system_instruction' => ['parts' => [['text' => $system]]],
                    'contents'           => $contents,
                    'generationConfig'   => [
                        'maxOutputTokens' => 2048,
                        'temperature'     => 0.7,
                    ],
                ]);

            if (!$response->successful()) {
                Log::error('Gemini API error', ['status' => $response->status(), 'body' => $response->body()]);
                return 'Sorry, I encountered an error. Please try again.';
            }

            return trim($response->json('candidates.0.content.parts.0.text', ''));
        } catch (\Throwable $e) {
            Log::error('Gemini call failed', ['error' => $e->getMessage()]);
            return 'Sorry, I encountered an error. Please try again.';
        }
    }

    private function fallbackToGemini(string $system, string $message, array $history): string
    {
        if (!empty($this->geminiKey)) {
            return $this->callGemini($system, $message, $history);
        }
        return "AI assistant is not configured. Please add GROQ_API_KEY to your environment settings.";
    }

    // ─── System Prompts ───────────────────────────────────────────────────────

    private function buildPublicSystemPrompt(): string
    {
        $categories = Category::active()->pluck('name')->join(', ');
        $settings   = SiteSetting::getMany(['contact_phone', 'contact_email', 'contact_address', 'whatsapp_number']);

        return <<<PROMPT
You are the T-AKOMZ AI Assistant, a helpful and knowledgeable customer service AI for T-Akomz Agro Estates Ltd — a premium Nigerian agricultural farm and e-commerce company.

ABOUT T-AKOMZ:
- 50-acre certified farm in Ido Ekiti, Ekiti State, Nigeria producing fresh, organic farm products
- Founded in 2018, over 200 happy customers
- Products: {$categories}
- Contact: {$settings['contact_phone']}, {$settings['contact_email']}
- Address: {$settings['contact_address']}

YOUR CAPABILITIES:
- Help customers find products and answer questions about them
- Explain delivery options (Standard ₦1,500 | Express ₦3,500 | Pickup free)
- Help with order tracking and account questions
- Share information about farm practices, organic certification, freshness
- Suggest products based on customer needs
- Handle complaints with empathy and escalate when needed

RULES:
- ONLY respond to topics related to T-Akomz Agro Estates and its products/services
- For off-topic questions, politely explain you can only help with T-Akomz matters
- Always be warm, professional, and helpful
- Format prices in Nigerian Naira (₦)
- If a customer wants to add a product to cart, tell them the product name and say "I've found it for you below" — the system will show the product card automatically
- Never make up product prices — if unsure, direct to the shop page
PROMPT;
    }

    private function buildAdminSystemPrompt(): string
    {
        return <<<PROMPT
You are the T-AKOMZ Admin AI, a powerful business intelligence assistant for T-Akomz Agro Estates Ltd admin panel.

You help admin users with:
- Writing compelling product descriptions (SEO-optimized, professional)
- Creating business plans, investment proposals, and market analysis for Nigerian agri-business
- Generating blog post content about farming, nutrition, and food topics
- Analyzing sales data and providing actionable insights
- Writing marketing copy, email campaigns, and social media posts
- Answering questions about farm operations, inventory management, and e-commerce
- Generating reports and summaries

CONTEXT:
- Company: T-Akomz Agro Estates Ltd (Nigerian farm + e-commerce)
- Farm Location: Oke-Ido Road, Ido Ekiti, Ekiti State, Nigeria
- Products: Poultry, Eggs, Livestock, Crop Produce, Dairy, Organic Inputs, Farm Boxes
- Currency: Nigerian Naira (₦)
- Market: Nigeria (primary Lagos, with nationwide delivery)

Always produce professional, well-structured content. For business documents, use proper formatting with headings and sections.
PROMPT;
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    private function extractProductSuggestions(string $reply, string $query): ?array
    {
        $keywords = ['product', 'egg', 'chicken', 'poultry', 'goat', 'milk', 'yam', 'maize', 'compost', 'box', 'found it for you'];
        $hasProductRef = false;
        foreach ($keywords as $kw) {
            if (stripos($reply, $kw) !== false || stripos($query, $kw) !== false) {
                $hasProductRef = true;
                break;
            }
        }

        if (!$hasProductRef) {
            return null;
        }

        // Search products matching query terms
        $words    = preg_split('/\s+/', strtolower($query));
        $relevant = array_filter($words, fn($w) => strlen($w) > 3);

        if (empty($relevant)) {
            return null;
        }

        $query = Product::active()->inStock()->with('images', 'category');
        foreach (array_slice($relevant, 0, 3) as $word) {
            $query->orWhere('name', 'like', "%{$word}%");
        }

        return $query->limit(3)->get()->map(fn($p) => [
            'id'            => $p->id,
            'name'          => $p->name,
            'price'         => $p->formatted_price,
            'image'         => $p->primary_image_url,
            'url'           => route('shop.product', [$p->category->slug, $p->slug]),
            'category'      => $p->category->name,
            'in_stock'      => $p->stock > 0,
        ])->values()->toArray() ?: null;
    }

    private function extractJson(string $text): string
    {
        if (preg_match('/```json\s*([\s\S]+?)\s*```/', $text, $m)) {
            return $m[1];
        }
        if (preg_match('/\{[\s\S]+\}/', $text, $m)) {
            return $m[0];
        }
        return $text;
    }
}
