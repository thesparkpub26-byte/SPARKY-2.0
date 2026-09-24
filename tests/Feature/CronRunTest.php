<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\MakesData;
use Tests\TestCase;

/** /api/cron/run: what an outside timer calls every minute on a host without cron. */
class CronRunTest extends TestCase
{
    use MakesData, RefreshDatabase;

    private const SECRET = 'a-long-random-secret-of-at-least-24-characters';

    private function dueArticle(): Article
    {
        return $this->makeArticle(['status' => 'scheduled', 'scheduled_at' => now()->subMinutes(5), 'published_at' => null]);
    }

    public function test_it_does_not_exist_until_a_secret_is_configured(): void
    {
        config(['security.cron_secret' => null]);

        $this->getJson('/api/cron/run', ['Authorization' => 'Bearer ' . self::SECRET])->assertNotFound();
        $this->getJson('/api/cron/run', ['Authorization' => 'Bearer '])->assertNotFound();
    }

    public function test_a_short_secret_counts_as_not_configured(): void
    {
        config(['security.cron_secret' => 'short']);

        $this->getJson('/api/cron/run', ['Authorization' => 'Bearer short'])->assertNotFound();
    }

    public function test_a_missing_or_wrong_key_runs_nothing(): void
    {
        config(['security.cron_secret' => self::SECRET]);
        $article = $this->dueArticle();

        $this->getJson('/api/cron/run')->assertForbidden();
        $this->getJson('/api/cron/run', ['Authorization' => 'Bearer wrong'])->assertForbidden();
        $this->getJson('/api/cron/run?key=' . self::SECRET)->assertForbidden();   // only the header counts, never the address

        $this->assertSame('scheduled', $article->fresh()->status);
    }

    public function test_the_right_key_runs_the_scheduled_jobs(): void
    {
        config(['security.cron_secret' => self::SECRET]);
        $due = $this->dueArticle();
        $later = $this->makeArticle(['status' => 'scheduled', 'scheduled_at' => now()->addDay(), 'published_at' => null]);

        $this->getJson('/api/cron/run', ['Authorization' => 'Bearer ' . self::SECRET])->assertOk()->assertJson(['ok' => true]);

        $this->assertSame('published', $due->fresh()->status, 'the article whose time had come is live');
        $this->assertSame('scheduled', $later->fresh()->status, 'the one for tomorrow is not');
    }

    public function test_a_post_works_too_because_timers_differ(): void
    {
        config(['security.cron_secret' => self::SECRET]);

        $this->postJson('/api/cron/run', [], ['Authorization' => 'Bearer ' . self::SECRET])->assertOk();
    }
}
