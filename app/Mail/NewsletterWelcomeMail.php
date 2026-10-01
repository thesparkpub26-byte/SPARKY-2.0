<?php

namespace App\Mail;

use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    /** Creates the welcome email for the person who just subscribed. */
    public function __construct(public NewsletterSubscriber $subscriber) {}

    /** Sets the subject of the welcome email. */
    public function envelope(): Envelope
    {
        return new Envelope(subject: "You're subscribed to TheSPARK");
    }

    /** Chooses the welcome email's template and gives it the unsubscribe link and the site address. */
    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter-welcome',
            with: ['unsubscribeUrl' => $this->subscriber->unsubscribeUrl(), 'siteUrl' => url('/')],
        );
    }
}
