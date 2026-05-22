<?php
namespace App\Mail;
use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StaffWelcome extends Mailable
{
    use SerializesModels;
    public function __construct(
        public User $user,
        public string $temporaryPassword,
        public string $role
    ) {}
    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Welcome to T-Akomz Agro Estates — Your Staff Account is Ready');
    }
    public function content(): Content
    {
        return new Content(view: 'emails.staff-welcome');
    }
}
