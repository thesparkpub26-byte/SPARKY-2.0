<?php

namespace App\Mail;

use Illuminate\Support\Facades\Http;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\MessageConverter;

/**
 * Sends mail through Brevo's HTTPS API (port 443) instead of SMTP. Free Render web services block the SMTP ports
 * (25, 465, 587), so a normal mail server login can never connect from there. Selected with MAIL_MAILER=brevo.
 */
class BrevoTransport extends AbstractTransport
{
    private const ENDPOINT = 'https://api.brevo.com/v3/smtp/email';

    public function __construct(private readonly string $apiKey)
    {
        parent::__construct();
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $from = $email->getFrom()[0] ?? null;
        if (!$from) {
            throw new TransportException('Brevo needs a sender: set MAIL_FROM_ADDRESS.');
        }

        $payload = array_filter([
            'sender'      => array_filter(['email' => $from->getAddress(), 'name' => $from->getName() ?: null]),
            'to'          => $this->addresses($email->getTo()),
            'cc'          => $this->addresses($email->getCc()),
            'bcc'         => $this->addresses($email->getBcc()),
            'replyTo'     => $this->addresses($email->getReplyTo())[0] ?? null,
            'subject'     => $email->getSubject(),
            'htmlContent' => $this->body($email->getHtmlBody()),
            'textContent' => $this->body($email->getTextBody()),
        ]);

        try {
            $response = Http::withHeaders(['api-key' => $this->apiKey, 'accept' => 'application/json'])
                ->timeout(15)
                ->post(self::ENDPOINT, $payload);
        } catch (\Throwable $e) {
            throw new TransportException('Could not reach the Brevo API: ' . $e->getMessage(), 0, $e);
        }

        if ($response->failed()) {
            throw new TransportException(sprintf('Brevo API error %d: %s', $response->status(), $response->body()));
        }
    }

    /** @param Address[] $addresses */
    private function addresses(array $addresses): array
    {
        return array_values(array_map(
            fn (Address $a) => array_filter(['email' => $a->getAddress(), 'name' => $a->getName() ?: null]),
            $addresses,
        ));
    }

    /** A message body can be a string or an open stream. */
    private function body($body): ?string
    {
        return is_resource($body) ? stream_get_contents($body) : $body;
    }

    public function __toString(): string
    {
        return 'brevo';
    }
}
