<?php

namespace App\Mail;

use App\Models\WalletTransaction;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DirectExpenseAlert extends Mailable
{
    use SerializesModels;

    public function __construct(public WalletTransaction $transaction) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Direct Expense Logged — ' . $this->transaction->short_title . ' (₦' . number_format($this->transaction->amount, 2) . ')',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.direct-expense-alert');
    }
}
