<?php

namespace App\Mail;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmed extends Mailable
{
    use SerializesModels;

    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Order Confirmed — {$this->order->order_number} | T-Akomz Agro Estates",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.confirmed',
        );
    }

    public function attachments(): array
    {
        $order = $this->order->loadMissing('items', 'user');

        $pdf = Pdf::loadView('pdf.invoice', compact('order'))
                  ->setPaper('a4', 'portrait');

        return [
            Attachment::fromData(
                fn () => $pdf->output(),
                "invoice-{$order->order_number}.pdf"
            )->withMime('application/pdf'),
        ];
    }
}
