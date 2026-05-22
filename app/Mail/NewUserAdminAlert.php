<?php
namespace App\Mail;
use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewUserAdminAlert extends Mailable
{
    use SerializesModels;
    public function __construct(public User $user) {}
    public function envelope(): Envelope
    {
        return new Envelope(subject: '[NEW CUSTOMER] ' . $this->user->name . ' just registered');
    }
    public function content(): Content
    {
        return new Content(view: 'emails.admin.new-user');
    }
}
