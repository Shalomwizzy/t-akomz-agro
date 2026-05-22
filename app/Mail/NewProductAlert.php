<?php

namespace App\Mail;

use App\Models\Product;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewProductAlert extends Mailable
{
    use SerializesModels;

    public function __construct(public Product $product) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New Arrival: {$this->product->name} | T-Akomz Agro Estates",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-product',
        );
    }
}
