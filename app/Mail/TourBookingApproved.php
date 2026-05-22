<?php
namespace App\Mail;
use App\Models\FarmTourBooking;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TourBookingApproved extends Mailable
{
    use SerializesModels;
    public function __construct(public FarmTourBooking $booking) {}
    public function envelope(): Envelope
    {
        return new Envelope(subject: '✅ Your Farm Tour is Approved! — ' . $this->booking->reference);
    }
    public function content(): Content
    {
        return new Content(view: 'emails.tour.approved');
    }
}
