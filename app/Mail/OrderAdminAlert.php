<?php
namespace App\Mail;
use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderAdminAlert extends Mailable
{
    use SerializesModels;
    public function __construct(public Order $order) {}
    public function envelope(): Envelope
    {
        return new Envelope(subject: '[NEW ORDER] ' . $this->order->order_number . ' — ₦' . number_format($this->order->total));
    }
    public function content(): Content
    {
        return new Content(view: 'emails.orders.admin-alert');
    }
}
