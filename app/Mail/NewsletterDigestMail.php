<?php

namespace App\Mail;

use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterDigestMail extends Mailable
{
    use Queueable, SerializesModels;

    /** @param array<int, array{title: string, excerpt: string, category: ?string, date: ?string, url: string, image: ?string}> $stories */
    public function __construct(public NewsletterSubscriber $subscriber, public array $stories) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'The latest from TheSPARK');
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter-digest',
            with: [
                'stories'        => $this->stories,
                'unsubscribeUrl' => $this->subscriber->unsubscribeUrl(),
                'siteUrl'        => url('/'),
            ],
        );
    }
}
