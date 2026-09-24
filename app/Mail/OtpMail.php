<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    /** $purpose is 'signup' (confirm a new account) or 'reset' (forgot password). */
    public function __construct(
        public string $otpCode,
        public string $recipientName,
        public string $purpose = 'signup',
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->purpose === 'reset' ? 'Reset your TheSPARK password' : 'Your TheSPARK Verification Code',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.otp',
            with: [
                'otpCode'       => $this->otpCode,
                'recipientName' => $this->recipientName,
                'purpose'       => $this->purpose,
            ],
        );
    }
}
