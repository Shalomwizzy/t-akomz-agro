<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterConfirmation extends Mailable
{
    use SerializesModels;

    public function __construct(public string $email) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "You're Subscribed — T-Akomz Farm Updates",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.newsletter-confirmation');
    }
}
