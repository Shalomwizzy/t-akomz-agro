<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentFailed extends Mailable
{
    use SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Payment Not Completed — {$this->order->order_number} | T-Akomz Agro Estates",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.orders.payment-failed');
    }
}
