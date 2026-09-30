<?php

namespace Tests\Feature;

use App\Mail\OtpMail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class BrevoMailTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'mail.default'      => 'brevo',
            'mail.from.address' => 'hello@example.test',
            'mail.from.name'    => 'TheSPARK',
            'services.brevo.key' => 'test-key',
        ]);
    }

    public function test_mail_goes_out_through_the_brevo_https_api(): void
    {
        Http::fake(['api.brevo.com/*' => Http::response(['messageId' => 'x'], 201)]);

        Mail::to('reader@example.test')->send(new OtpMail('123456', 'Maria'));

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.brevo.com/v3/smtp/email'
                && $request->hasHeader('api-key', 'test-key')
                && $request['sender']['email'] === 'hello@example.test'
                && $request['to'][0]['email'] === 'reader@example.test'
                && str_contains($request['htmlContent'] ?? $request['textContent'], '123456');
        });
    }

    public function test_an_api_error_is_reported_as_a_failed_send(): void
    {
        Http::fake(['api.brevo.com/*' => Http::response(['message' => 'Key not found'], 401)]);

        $this->expectException(\Symfony\Component\Mailer\Exception\TransportException::class);
        $this->expectExceptionMessage('Brevo API error 401');

        Mail::to('reader@example.test')->send(new OtpMail('123456', 'Maria'));
    }
}
