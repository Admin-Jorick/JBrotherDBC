<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingDeclinedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $reason;

    public function __construct(Booking $booking, $reason)
    {
        $this->booking = $booking;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('Booking Declined')
                    ->view('emails.booking_declined');
    }
}

