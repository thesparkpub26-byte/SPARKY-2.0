<?php

namespace Tests\Feature;

use App\Mail\OtpMail;
use App\Models\Article;
use App\Models\OtpVerification;
use App\Models\PasswordResetCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\Sanctum;
use Tests\Concerns\MakesData;
use Tests\TestCase;

class AccountSecurityTest extends TestCase
{
    use MakesData, RefreshDatabase;

    // ── Forgot password ──────────────────────────────────────────────────────

    public function test_forgot_password_end_to_end(): void
    {
        Mail::fake();
        $user = $this->makeUser('reader');
        $user->createToken('old-device');

        $this->postJson('/api/password/forgot', ['email' => $user->email])->assertOk();
        $code = null;
        Mail::assertSent(OtpMail::class, function (OtpMail $mail) use (&$code) {
            $code = $mail->otpCode;
            return $mail->purpose === 'reset';
        });

        $this->postJson('/api/password/verify', ['email' => $user->email, 'otp' => $code === '000000' ? '111111' : '000000'])->assertStatus(422);
        $token = $this->postJson('/api/password/verify', ['email' => $user->email, 'otp' => $code])->assertOk()->json('reset_token');
        $this->postJson('/api/password/verify', ['email' => $user->email, 'otp' => $code])->assertStatus(422); // a code works once

        $payload = ['email' => $user->email, 'reset_token' => $token, 'password' => 'brandnewpass1', 'password_confirmation' => 'brandnewpass1'];
        $this->postJson('/api/password/reset', array_merge($payload, ['password_confirmation' => 'different']))->assertStatus(422);
        $this->postJson('/api/password/reset', $payload)->assertOk();
        $this->postJson('/api/password/reset', $payload)->assertStatus(422); // a token works once

        $this->assertSame(0, $user->tokens()->count(), 'signed out everywhere');
        $this->postJson('/api/login', ['email' => $user->email, 'password' => 'brandnewpass1'])->assertOk();
    }

    public function test_forgot_password_does_not_reveal_which_emails_have_accounts(): void
    {
        Mail::fake();
        $inactive = $this->makeUser('staff_writer', ['is_active' => false]);

        $known = $this->postJson('/api/password/forgot', ['email' => $this->makeUser('reader')->email]);
        $unknown = $this->postJson('/api/password/forgot', ['email' => 'nobody@nowhere.test']);
        $off = $this->postJson('/api/password/forgot', ['email' => $inactive->email]);

        $this->assertSame($known->json(), $unknown->json());
        $this->assertSame($known->json(), $off->json());
        Mail::assertSent(OtpMail::class, 1);
    }

    public function test_reset_code_is_burned_after_five_wrong_guesses(): void
    {
        Mail::fake();
        $user = $this->makeUser('reader');
        $this->postJson('/api/password/forgot', ['email' => $user->email]);
        $code = null;
        Mail::assertSent(OtpMail::class, function (OtpMail $m) use (&$code) { $code = $m->otpCode; return true; });
        $wrong = $code === '999999' ? '111111' : '999999';

        for ($i = 0; $i < 6; $i++) {
            $this->postJson('/api/password/verify', ['email' => $user->email, 'otp' => $wrong]);
        }

        $this->postJson('/api/password/verify', ['email' => $user->email, 'otp' => $code])->assertStatus(422);
        $this->assertSame(0, PasswordResetCode::count());
    }

    // ── Sign-in and sign-up ──────────────────────────────────────────────────

    public function test_five_wrong_passwords_lock_that_email_out(): void
    {
        $user = $this->makeUser('reader');

        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/login', ['email' => $user->email, 'password' => 'wrong-password'])->assertStatus(422);
        }

        $this->postJson('/api/login', ['email' => $user->email, 'password' => 'wrong-password'])->assertStatus(429);
        $this->postJson('/api/login', ['email' => $user->email, 'password' => 'password123'])->assertStatus(429);
        $this->postJson('/api/login', ['email' => 'someone@else.test', 'password' => 'x'])->assertStatus(422);
    }

    public function test_deactivated_staff_cannot_sign_in(): void
    {
        $user = $this->makeUser('staff_writer', ['is_active' => false]);

        $this->postJson('/api/login', ['email' => $user->email, 'password' => 'password123'])->assertForbidden();
    }

    public function test_signup_code_is_burned_after_five_wrong_guesses_and_expiry_is_not_extended(): void
    {
        OtpVerification::create(['name' => 'T', 'email' => 'new@example.test', 'password' => 'x', 'otp' => '123456', 'expires_at' => now()->addMinutes(2)]);
        $expiry = OtpVerification::first()->expires_at;

        $this->postJson('/api/register/verify-otp', ['email' => 'new@example.test', 'otp' => '000000'])->assertStatus(422);
        $this->assertTrue(OtpVerification::first()->expires_at->equalTo($expiry), 'a wrong guess must not renew the code');

        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/register/verify-otp', ['email' => 'new@example.test', 'otp' => '000000']);
        }
        // even the right code is useless now: the pending sign-up was thrown away
        $this->postJson('/api/register/verify-otp', ['email' => 'new@example.test', 'otp' => '123456'])->assertNotFound();
        $this->assertSame(0, OtpVerification::count());
    }

    // ── Own account ──────────────────────────────────────────────────────────

    public function test_changing_password_needs_the_current_one_and_signs_out_other_devices(): void
    {
        $user = $this->makeUser('reader', ['password' => 'oldpassword1']);
        $user->createToken('other-device');
        Sanctum::actingAs($user);
        $new = ['password' => 'newpassword1', 'password_confirmation' => 'newpassword1'];

        $this->postJson('/api/profile', $new)->assertStatus(422);
        $this->postJson('/api/profile', $new + ['current_password' => 'nope'])->assertStatus(422);
        $this->postJson('/api/profile', $new + ['current_password' => 'oldpassword1'])->assertOk();
        $this->assertSame(0, $user->tokens()->where('name', 'other-device')->count());
        $this->postJson('/api/profile', ['name' => 'Renamed'])->assertOk();
    }

    public function test_deleting_an_account_needs_the_password(): void
    {
        $user = $this->makeUser('reader', ['password' => 'oldpassword1']);
        Sanctum::actingAs($user);

        $this->deleteJson('/api/profile')->assertStatus(422);
        $this->deleteJson('/api/profile', ['password' => 'nope'])->assertStatus(422);
        $this->deleteJson('/api/profile', ['password' => 'oldpassword1'])->assertOk();
        $this->assertNull($user->fresh());
    }

    public function test_staff_cannot_delete_their_own_account(): void
    {
        foreach (['admin', 'eic', 'section_editor', 'staff_writer', 'staff_artist', 'staff_broadcaster'] as $role) {
            $user = $this->makeUser($role, ['password' => 'oldpassword1']);
            Sanctum::actingAs($user);

            $this->deleteJson('/api/profile', ['password' => 'oldpassword1'])->assertForbidden();
            $this->assertNotNull($user->fresh(), "{$role} account should not be deleted");
        }
    }

    public function test_sign_in_tokens_expire_and_deactivation_signs_people_out(): void
    {
        $user = $this->makeUser('reader');
        $token = $user->createToken('t');

        $this->withToken($token->plainTextToken)->getJson('/api/me')->assertOk();

        $token->accessToken->forceFill(['created_at' => now()->subDays(8)])->save();
        $this->app['auth']->forgetGuards();
        $this->withToken($token->plainTextToken)->getJson('/api/me')->assertUnauthorized();

        $victim = $this->makeUser('reader');
        $victim->createToken('x');
        Sanctum::actingAs($this->makeUser('eic'));
        $this->putJson("/api/users/{$victim->id}", ['is_active' => false])->assertOk();
        $this->assertSame(0, $victim->tokens()->count());
    }

    // ── Content and browser protections ──────────────────────────────────────

    public function test_saved_article_html_is_cleaned(): void
    {
        Sanctum::actingAs($this->makeUser('staff_writer'));

        $body = '<p onclick="x()">hi</p><script>alert(1)</script><img src=x onerror=alert(2)><a href="javascript:alert(3)">l</a>';
        $this->postJson('/api/articles', ['title' => 'XSS', 'content' => $body])
            ->assertCreated()
            ->assertJsonPath('content', '<p>hi</p><a>l</a>');
    }

    public function test_script_urls_are_refused(): void
    {
        Sanctum::actingAs($this->makeUser('staff_writer'));

        $this->postJson('/api/articles', ['title' => 'V', 'video_url' => 'javascript:alert(1)'])->assertStatus(422);
        $this->postJson('/api/articles', ['title' => 'C', 'cover_image' => 'javascript:alert(1)'])->assertStatus(422);
        $this->postJson('/api/articles', ['title' => 'C', 'cover_image' => '/storage/article-media/a.jpg'])->assertCreated();
    }

    public function test_responses_carry_security_headers_and_cors_is_limited_to_the_site(): void
    {
        $response = $this->getJson('/api/reader/carousel');
        $response->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');

        // a browser only lets a page read the response if this header names its own origin
        $foreign = $this->withHeader('Origin', 'http://evil.example')->getJson('/api/reader/carousel')->headers->get('Access-Control-Allow-Origin');
        $this->assertNotSame('http://evil.example', $foreign);
        $this->assertNotSame('*', $foreign);
        $this->withHeader('Origin', 'http://localhost')->getJson('/api/reader/carousel')->assertHeader('Access-Control-Allow-Origin', 'http://localhost');
    }

    public function test_an_articles_link_preview_escapes_its_title(): void
    {
        $article = $this->makeArticle(['title' => 'A "quote" <script>x()</script>']);

        $html = $this->get("/article/{$article->id}")->assertOk()->getContent();

        $this->assertStringNotContainsString('<script>x()', $html);
        $this->assertStringContainsString('property="og:title"', $html);
    }
}
