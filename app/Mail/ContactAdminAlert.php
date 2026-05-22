<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactAdminAlert extends Mailable
{
    use SerializesModels;

    public function __construct(
        public string $senderName,
        public string $senderEmail,
        public string $senderPhone,
        public string $topic,
        public string $userMessage,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[Contact Form] {$this->topic}",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.contact-admin');
    }
}
