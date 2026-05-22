<?php

namespace App\Mail;

use App\Models\WalletTransaction;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ExpenseRejected extends Mailable
{
    use SerializesModels;

    public function __construct(public WalletTransaction $transaction) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Expense Request Declined — ' . $this->transaction->short_title,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.expense-rejected');
    }
}
