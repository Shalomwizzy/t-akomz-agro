<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterConfirmation;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate(['email' => ['required', 'email', 'max:255']]);

        $email      = strtolower($request->email);
        $subscriber = NewsletterSubscriber::where('email', $email)->first();
        $isNew      = !$subscriber || !$subscriber->is_active;

        NewsletterSubscriber::updateOrCreate(
            ['email' => $email],
            ['is_active' => true, 'subscribed_at' => now()]
        );

        // Only send confirmation on first-time subscribe
        if ($isNew) {
            try {
                Mail::to($email)->send(new NewsletterConfirmation($email));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Newsletter confirmation email failed', ['error' => $e->getMessage()]);
            }
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Subscribed successfully!']);
        }

        return back()->with('success', 'You are now subscribed! Check your inbox for a confirmation email.');
    }
}
