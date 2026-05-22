<?php

namespace App\Mail;

use App\Models\Worker;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WorkerWelcome extends Mailable
{
    use SerializesModels;

    public function __construct(public Worker $worker) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to T-Akomz Agro Estates — ' . $this->worker->full_name,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.worker-welcome');
    }
}
