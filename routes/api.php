<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PaymentWebhookController;
use App\Http\Controllers\Api\AiAssistantController;

// ─── Payment Webhooks (no CSRF, signature-verified) ─────────────────────────

Route::post('/payment/paystack/webhook',    [PaymentWebhookController::class, 'paystack'])->name('webhook.paystack');
Route::post('/payment/flutterwave/webhook', [PaymentWebhookController::class, 'flutterwave'])->name('webhook.flutterwave');

// ─── AI Assistant — Public (rate-limited: 20 req/min per IP) ────────────────

Route::middleware('throttle:20,1')->post('/ai/chat', [AiAssistantController::class, 'chat'])->name('ai.chat');

// ─── AI Assistant — Admin (auth + admin role required) ───────────────────────

Route::middleware(['auth', 'admin'])->prefix('ai/admin')->group(function () {
    Route::post('/chat',                [AiAssistantController::class, 'adminChat'])->name('ai.admin.chat');
    Route::post('/product-description', [AiAssistantController::class, 'generateProductDescription'])->name('ai.admin.product-description');
    Route::post('/product-content',     [AiAssistantController::class, 'generateProductContent'])->name('ai.admin.product-content');
    Route::post('/business-plan',       [AiAssistantController::class, 'generateBusinessPlan'])->name('ai.admin.business-plan');
    Route::post('/blog-post',           [AiAssistantController::class, 'generateBlogPost'])->name('ai.admin.blog-post');
});
