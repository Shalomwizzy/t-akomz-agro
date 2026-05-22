<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AiAssistantService;
use Illuminate\Http\Request;

class AiAssistantController extends Controller
{
    public function __construct(private AiAssistantService $ai) {}

    // ─── Public chat ──────────────────────────────────────────────────────────

    public function chat(Request $request)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'history' => ['nullable', 'array', 'max:20'],
        ]);

        $result = $this->ai->chat(
            $request->message,
            $request->input('history', [])
        );

        return response()->json($result);
    }

    // ─── Admin tools ──────────────────────────────────────────────────────────

    public function adminChat(Request $request)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'history' => ['nullable', 'array', 'max:20'],
        ]);

        $reply = $this->ai->adminChat(
            $request->message,
            $request->input('history', [])
        );

        return response()->json(['reply' => $reply]);
    }

    public function generateProductDescription(Request $request)
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

    public function generateProductContent(Request $request)
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

    public function generateBusinessPlan(Request $request)
    {
        $request->validate([
            'section' => ['required', 'string', 'max:200'],
            'context' => ['nullable', 'string', 'max:1000'],
        ]);

        $content = $this->ai->generateBusinessPlan(
            $request->section,
            $request->context
        );

        return response()->json(['content' => $content]);
    }

    public function generateBlogPost(Request $request)
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
}
