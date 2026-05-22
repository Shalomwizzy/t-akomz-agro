<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactAutoReply extends Mailable
{
    use SerializesModels;

    public function __construct(
        public string $senderName,
        public string $topic,
        public string $userMessage,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'We Received Your Message — T-Akomz Agro Estates',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.contact-reply');
    }
}
