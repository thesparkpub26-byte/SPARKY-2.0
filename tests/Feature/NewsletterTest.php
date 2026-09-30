<?php

namespace Tests\Feature;

use App\Mail\NewsletterDigestMail;
use App\Mail\NewsletterWelcomeMail;
use App\Models\NewsletterSubscriber;
use App\Models\Notification;
use App\Models\OtpVerification;
use App\Models\PageView;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\Sanctum;
use Tests\Concerns\MakesData;
use Tests\TestCase;

class NewsletterTest extends TestCase
{
    use MakesData, RefreshDatabase;

    public function test_subscribing_sends_a_welcome_and_is_idempotent(): void
    {
        Mail::fake();

        $this->postJson('/api/newsletter/subscribe', ['email' => 'Fan@Example.com'])->assertCreated();
        $this->postJson('/api/newsletter/subscribe', ['email' => 'fan@example.com'])->assertOk();
        $this->postJson('/api/newsletter/subscribe', ['email' => 'not-an-email'])->assertStatus(422);

        Mail::assertSent(NewsletterWelcomeMail::class, 1);
        $this->assertSame(1, NewsletterSubscriber::count());
    }

    public function test_a_signed_in_member_subscribes_and_unsubscribes_from_the_card(): void
    {
        Mail::fake();
        $reader = $this->makeUser('reader');

        $this->getJson('/api/newsletter/me')->assertUnauthorized();
        $this->postJson('/api/newsletter/me/subscribe')->assertUnauthorized();

        Sanctum::actingAs($reader);
        $this->getJson('/api/newsletter/me')->assertOk()->assertJsonPath('subscribed', false);

        $this->postJson('/api/newsletter/me/subscribe')->assertCreated()->assertJsonPath('subscribed', true);
        $this->getJson('/api/newsletter/me')->assertJsonPath('subscribed', true);
        Mail::assertSent(NewsletterWelcomeMail::class, 1);

        $this->postJson('/api/newsletter/me/unsubscribe')->assertOk()->assertJsonPath('subscribed', false);
        $this->getJson('/api/newsletter/me')->assertJsonPath('subscribed', false);

        // Pressing Subscribe again re-joins the same record
        $this->postJson('/api/newsletter/me/subscribe')->assertCreated();
        $this->assertSame(1, NewsletterSubscriber::count());
        $this->getJson('/api/newsletter/me')->assertJsonPath('subscribed', true);
    }

    public function test_signing_up_does_not_subscribe_anyone_automatically(): void
    {
        Mail::fake();
        OtpVerification::create(['name' => 'New Reader', 'email' => 'new@example.test', 'password' => bcrypt('Sturdy-pass-88'), 'otp' => '123456', 'expires_at' => now()->addMinutes(10)]);

        $this->postJson('/api/register/verify-otp', ['email' => 'new@example.test', 'otp' => '123456'])->assertCreated();

        $this->assertSame(0, NewsletterSubscriber::count());
    }

    public function test_only_admin_and_eic_can_see_the_subscriber_list(): void
    {
        Sanctum::actingAs($this->makeUser('staff_writer'));
        $this->getJson('/api/newsletter/subscribers')->assertForbidden();

        Sanctum::actingAs($this->makeUser('eic'));
        $this->getJson('/api/newsletter/subscribers')->assertOk();
    }

    public function test_weekly_digest_goes_to_active_subscribers_only(): void
    {
        Mail::fake();
        $this->makeArticle(['title' => 'This week', 'published_at' => now()->subDay()]);
        $stay = NewsletterSubscriber::create(['email' => 'stay@example.test', 'unsubscribe_token' => 'a']);
        NewsletterSubscriber::create(['email' => 'left@example.test', 'unsubscribe_token' => 'b', 'unsubscribed_at' => now()]);

        $this->artisan('newsletter:digest')->assertSuccessful();

        Mail::assertSent(NewsletterDigestMail::class, 1);
        Mail::assertSent(NewsletterDigestMail::class, fn ($m) => $m->hasTo($stay->email));
    }

    public function test_no_digest_is_sent_when_nothing_was_published(): void
    {
        Mail::fake();
        NewsletterSubscriber::create(['email' => 'stay@example.test', 'unsubscribe_token' => 'a']);
        $this->makeArticle(['published_at' => now()->subDays(30)]);

        $this->artisan('newsletter:digest')->assertSuccessful();

        Mail::assertNothingSent();
    }

    public function test_unsubscribing_with_the_emailed_link(): void
    {
        $sub = NewsletterSubscriber::create(['email' => 'stay@example.test', 'unsubscribe_token' => 'tok']);

        $this->postJson('/api/newsletter/unsubscribe', ['token' => 'wrong'])->assertNotFound();
        $this->postJson('/api/newsletter/unsubscribe', ['token' => 'tok'])->assertOk();

        $this->assertNotNull($sub->fresh()->unsubscribed_at);
    }

    public function test_old_data_is_pruned_but_recent_data_is_kept(): void
    {
        $old = PageView::create(['page_path' => '/a']);
        $old->forceFill(['created_at' => now()->subDays(200)])->save();
        PageView::create(['page_path' => '/b']);
        OtpVerification::create(['name' => 'T', 'email' => 'old@example.test', 'password' => 'x', 'otp' => '123456', 'expires_at' => now()->subDays(3)]);

        $reader = $this->makeUser('reader');
        Notification::create(['user_id' => $reader->id, 'title' => 'Old', 'message' => 'm', 'type' => 'general', 'read_at' => now()->subDays(90)]);
        Notification::create(['user_id' => $reader->id, 'title' => 'Unread', 'message' => 'm', 'type' => 'general']);

        $this->artisan('maintenance:prune')->assertSuccessful();

        $this->assertSame(1, PageView::count());
        $this->assertSame(0, OtpVerification::count());
        $this->assertSame(1, Notification::count());
    }
}
