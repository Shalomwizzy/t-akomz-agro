<?php

namespace App\Mail;

use App\Models\Product;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LowStockAlert extends Mailable
{
    use SerializesModels;

    public function __construct(public Product $product) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "⚠️ Low Stock Alert — {$this->product->name} | T-Akomz",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.admin.low-stock');
    }
}
