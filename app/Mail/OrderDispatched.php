<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderDispatched extends Mailable
{
    use SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Your Order Is On Its Way — {$this->order->order_number} | T-Akomz",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.dispatched',
        );
    }
}
