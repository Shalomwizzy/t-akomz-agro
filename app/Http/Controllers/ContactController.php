<?php

namespace App\Http\Controllers;

use App\Mail\ContactAdminAlert;
use App\Mail\ContactAutoReply;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::getMany([
            'contact_phone', 'contact_phone_2', 'contact_email',
            'contact_address', 'whatsapp_number', 'google_maps_embed',
        ]);

        $seoTitle       = 'Contact Us';
        $seoDescription = 'Get in touch with T-Akomz Agro Estates. Call, WhatsApp, or visit us at Oke-Ido Road, Ido Ekiti, Ekiti State, Nigeria.';

        return view('pages.contact', compact('settings', 'seoTitle', 'seoDescription'));
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email'],
            'phone'   => ['nullable', 'string', 'max:20'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        $adminEmail = SiteSetting::adminEmail();

        // Persist to database regardless of email outcome
        \App\Models\ContactMessage::create($data);

        try {
            // Auto-reply to sender
            Mail::to($data['email'])->send(
                new ContactAutoReply($data['name'], $data['subject'], $data['message'])
            );

            // Alert to admin with full message + reply-to
            Mail::to($adminEmail)
                ->send(new ContactAdminAlert(
                    $data['name'],
                    $data['email'],
                    $data['phone'] ?? '',
                    $data['subject'],
                    $data['message'],
                ));

            return back()->with('success', 'Your message has been sent! We will get back to you within 24 hours.');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Contact form email failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Could not send your message. Please try WhatsApp or phone us directly.')->withInput();
        }
    }
}
