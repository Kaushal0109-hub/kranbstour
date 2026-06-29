<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoginOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $otp,
        public string $purpose,
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->purpose === 'register'
            ? 'Verify your email — '.config('site.name')
            : 'Your login code — '.config('site.name');

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.login-otp',
        );
    }
}
