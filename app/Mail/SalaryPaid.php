<?php

namespace App\Mail;

use App\Models\Payroll;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SalaryPaid extends Mailable
{
    use SerializesModels;

    public function __construct(public Payroll $payroll) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Salary Payment Received — ' . $this->payroll->period_label,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.salary-paid');
    }
}
