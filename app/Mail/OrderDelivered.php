<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderDelivered extends Mailable
{
    use SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Delivered! Your Order {$this->order->order_number} Has Arrived | T-Akomz",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.delivered',
        );
    }
}
