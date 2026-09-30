<?php

namespace Tests\Feature;

use App\Mail\OtpMail;
use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\Sanctum;
use Tests\Concerns\MakesData;
use Tests\TestCase;

/** Hostile input, password policy and browser-side protections. */
class HardeningTest extends TestCase
{
    use MakesData, RefreshDatabase;

    private const INJECTIONS = ["' OR '1'='1", "' OR 1=1 --", "admin'--", '" OR ""="', "'; DROP TABLE users; --", "1; SELECT SLEEP(5)", "\\' UNION SELECT * FROM users --"];

    // ── SQL injection ────────────────────────────────────────────────────────

    public function test_sign_in_cannot_be_bypassed_with_injection_strings(): void
    {
        $user = $this->makeUser('admin', ['email' => 'boss@example.test', 'password' => 'Sturdy-pass-88']);

        foreach (self::INJECTIONS as $attack) {
            $this->postJson('/api/login', ['email' => $attack, 'password' => $attack])->assertStatus(422);
            $this->postJson('/api/login', ['email' => 'boss@example.test', 'password' => $attack])->assertStatus(422);
            $this->postJson('/api/login', ['email' => ['boss@example.test'], 'password' => 'Sturdy-pass-88'])->assertStatus(422);
            // a stray lockout from these attempts must not matter here
            $this->flushSession();
            \Illuminate\Support\Facades\RateLimiter::clear('login:boss@example.test|127.0.0.1');
        }

        $this->assertDatabaseHas('users', ['id' => $user->id]);
        $this->postJson('/api/login', ['email' => 'boss@example.test', 'password' => 'Sturdy-pass-88'])->assertOk();
    }

    public function test_injection_strings_in_search_and_filters_do_nothing(): void
    {
        $this->makeArticle(['title' => 'Real story']);

        foreach (self::INJECTIONS as $attack) {
            $this->getJson('/api/reader/search?' . http_build_query(['q' => $attack, 'category' => $attack, 'type' => $attack, 'sort' => $attack, 'period' => $attack]))->assertOk();
            $this->postJson('/api/newsletter/subscribe', ['email' => $attack])->assertStatus(422);
            $this->postJson('/api/password/forgot', ['email' => $attack])->assertStatus(422);
        }

        $this->assertTrue(Schema::hasTable('users'), 'nothing was dropped');
        $this->assertSame(1, Article::count());
    }

    public function test_list_filters_treat_input_as_data_never_as_sql(): void
    {
        $first = $this->makeArticle(['title' => 'One']);
        $this->makeArticle(['title' => 'Two']);
        Sanctum::actingAs($this->makeUser('eic'));

        // a numeric filter with SQL glued on matches nothing; it does not turn into "everything"
        $this->assertCount(0, $this->getJson('/api/articles?author_id=' . urlencode($first->author_id . ' OR 1=1'))->assertOk()->json());
        $this->assertCount(0, $this->getJson('/api/articles?status=' . urlencode("published' OR '1'='1"))->assertOk()->json());
        $this->assertCount(0, $this->getJson('/api/tasks?assignee_id=' . urlencode('1 OR 1=1'))->assertOk()->json());

        // arrays and other odd shapes are handled, not crashed on
        $this->getJson('/api/articles?status[]=a&author_id[]=1&credited_to[x]=1')->assertOk();
        $this->getJson('/api/users?role[]=admin')->assertOk();
        $this->getJson('/api/notifications?per_page=999999999&type[]=x')->assertOk();

        // a normal filter still works
        $this->assertCount(1, $this->getJson("/api/articles?author_id={$first->author_id}")->assertOk()->json());
    }

    public function test_no_raw_sql_is_built_by_gluing_text_together(): void
    {
        $rawCalls = '(?:whereRaw|orWhereRaw|selectRaw|orderByRaw|groupByRaw|havingRaw|DB::raw|DB::select|DB::statement|DB::unprepared)';
        $offenders = [];

        foreach ($this->phpFiles(app_path()) as $file) {
            foreach (file($file) as $number => $line) {
                if (!preg_match('/' . $rawCalls . '\(/', $line)) continue;

                $interpolated = preg_match('/' . $rawCalls . '\(\s*"[^"]*\$/', $line);             // "... $value ..."
                $concatenated = preg_match('/' . $rawCalls . '\([^)]*[\'"]\s*\.\s*\$/', $line);    // '...' . $value
                $unprepared   = str_contains($line, 'DB::unprepared');

                if ($interpolated || $concatenated || $unprepared) {
                    $offenders[] = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $file) . ':' . ($number + 1);
                }
            }
        }

        $this->assertSame([], $offenders, 'Raw SQL must use ? placeholders with bound values. Found: ' . implode(', ', $offenders));
    }

    /** @return iterable<string> */
    private function phpFiles(string $dir): iterable
    {
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS)) as $file) {
            if ($file->getExtension() === 'php') yield $file->getPathname();
        }
    }

    public function test_signing_up_reports_an_unreachable_mail_server_instead_of_crashing(): void
    {
        Mail::shouldReceive('to->send')->andThrow(new \RuntimeException('Connection timed out'));

        $this->postJson('/api/register/send-otp', [
            'name' => 'Maria Santos', 'email' => 'new.reader@example.test', 'password' => 'Gentle-river-5821', 'password_confirmation' => 'Gentle-river-5821',
        ])->assertStatus(503)->assertJsonPath('message', "We couldn't send the verification code right now. Please try again in a few minutes.");

        // No half-finished sign-up is left waiting for a code that never arrived
        $this->assertSame(0, \App\Models\OtpVerification::count());
    }

    // ── Passwords ────────────────────────────────────────────────────────────

    public function test_weak_passwords_are_refused_when_signing_up(): void
    {
        Mail::fake();
        $signup = fn (string $password) => $this->postJson('/api/register/send-otp', [
            'name' => 'Maria Santos', 'email' => 'maria.santos@example.test', 'password' => $password, 'password_confirmation' => $password,
        ]);

        $signup('short1')->assertStatus(422);                    // too short
        $signup('onlyletters')->assertStatus(422);               // no number
        $signup('12345678')->assertStatus(422);                  // no letter, and common
        $signup('password123')->assertStatus(422);               // on the common list
        $signup('Password-123')->assertStatus(422);              // common once punctuation is ignored
        $signup('maria.santos2026')->assertStatus(422);          // built from the email
        $signup('MariaSantos99')->assertStatus(422);             // built from the name

        $signup('Sturdy-pass-88')->assertOk();
        Mail::assertSent(OtpMail::class, 1);
    }

    public function test_the_same_policy_applies_to_profile_and_account_creation(): void
    {
        $user = $this->makeUser('reader', ['password' => 'Sturdy-pass-88']);
        Sanctum::actingAs($user);

        $this->postJson('/api/profile', ['current_password' => 'Sturdy-pass-88', 'password' => 'qwerty123', 'password_confirmation' => 'qwerty123'])->assertStatus(422);
        $this->postJson('/api/profile', ['current_password' => 'Sturdy-pass-88', 'password' => 'Another-good-77', 'password_confirmation' => 'Another-good-77'])->assertOk();

        Sanctum::actingAs($this->makeUser('admin'));
        $this->postJson('/api/users', ['name' => 'N', 'email' => 'n@example.test', 'password' => 'admin123', 'role' => 'reader'])->assertStatus(422);
    }

    // ── Browser protections ──────────────────────────────────────────────────

    public function test_pages_carry_a_content_security_policy(): void
    {
        $policy = $this->get('/')->headers->get('Content-Security-Policy-Report-Only')
            ?? $this->get('/')->headers->get('Content-Security-Policy');

        $this->assertNotNull($policy);
        foreach (["default-src 'self'", "object-src 'none'", "frame-ancestors 'self'", "base-uri 'self'", 'https://www.youtube.com'] as $expected) {
            $this->assertStringContainsString($expected, $policy);
        }
        $this->assertStringNotContainsString("'unsafe-eval'", $policy, 'scripts may not use eval');
        $this->assertDoesNotMatchRegularExpression("/script-src[^;]*'unsafe-inline'/", $policy);
    }

    public function test_scripts_and_styles_from_the_asset_cdn_are_allowed_only_when_one_is_configured(): void
    {
        config(['security.csp_mode' => 'enforce', 'app.asset_url' => null]);
        $this->assertStringNotContainsString('cdn.example.test', $this->get('/')->headers->get('Content-Security-Policy'));

        config(['app.asset_url' => 'https://cdn.example.test/some/path']);
        $policy = $this->get('/')->headers->get('Content-Security-Policy');

        foreach (['script-src', 'style-src', 'font-src'] as $directive) {
            $this->assertMatchesRegularExpression("/{$directive}[^;]*https:\/\/cdn\.example\.test(?!\/)/", $policy, "{$directive} should allow the CDN");
        }
        $this->assertStringNotContainsString('/some/path', $policy, 'only the origin is allowed, not a path');
    }

    public function test_the_policy_is_enforced_when_configured_and_can_be_switched_off(): void
    {
        config(['security.csp_mode' => 'enforce']);
        $this->assertNotNull($this->get('/')->headers->get('Content-Security-Policy'));

        config(['security.csp_mode' => 'off']);
        $response = $this->get('/');
        $this->assertNull($response->headers->get('Content-Security-Policy'));
        $this->assertNull($response->headers->get('Content-Security-Policy-Report-Only'));
    }

    public function test_api_answers_cannot_be_cached_or_framed(): void
    {
        // Personal data: never kept by browsers or shared proxies
        $this->getJson('/api/me')
            ->assertHeader('Content-Security-Policy', "default-src 'none'; frame-ancestors 'none'")
            ->assertHeader('Cache-Control', 'no-store, private');

        // Article pages carry the reader's own like / bookmark state
        $this->getJson('/api/reader/articles/1')->assertHeader('Cache-Control', 'no-store, private');
    }

    public function test_public_reader_lists_may_be_cached_briefly(): void
    {
        foreach (['carousel', 'articles', 'category-articles', 'videos', 'issues', 'gallery'] as $list) {
            $response = $this->getJson("/api/reader/{$list}")
                ->assertHeader('Content-Security-Policy', "default-src 'none'; frame-ancestors 'none'");

            $this->assertTrue($response->headers->hasCacheControlDirective('public'), "{$list} should be cacheable");
            $this->assertStringNotContainsString('no-store', $response->headers->get('Cache-Control'));
            $this->assertNotNull($response->headers->get('ETag'), "{$list} should carry an ETag");
        }
    }

    public function test_a_published_article_shows_up_in_the_cached_reader_list_immediately(): void
    {
        $this->assertSame([], $this->getJson('/api/reader/articles')->json());

        $article = $this->makeArticle(['title' => 'Fresh news']);

        $this->assertSame(['Fresh news'], array_column($this->getJson('/api/reader/articles')->json(), 'title'));

        $article->update(['title' => 'Updated news']);
        $this->assertSame(['Updated news'], array_column($this->getJson('/api/reader/articles')->json(), 'title'));

        $article->delete();
        $this->assertSame([], $this->getJson('/api/reader/articles')->json());
    }

    public function test_visitors_are_not_given_session_or_csrf_cookies(): void
    {
        $response = $this->get('/');

        $this->assertSame([], $response->headers->getCookies(), 'sign-in uses tokens, so there is no cookie to steal or ride');
    }

    public function test_unknown_pages_and_files_do_not_reveal_internals(): void
    {
        config(['app.debug' => false]); // as on a live site

        $this->getJson('/api/does-not-exist')->assertNotFound();
        // a missing upload is an error status, not the app's page dressed up as a 200
        $this->assertContains($this->get('/storage/missing-file.jpg')->getStatusCode(), [403, 404]);
        $body = $this->getJson('/api/reader/articles/not-a-number')->getContent();

        $this->assertStringNotContainsString('vendor', $body);
        $this->assertStringNotContainsString(base_path(), $body);
    }

    public function test_signed_out_callers_get_a_401_not_a_server_error_whatever_headers_they_send(): void
    {
        // no "Accept: application/json", as from a script, a crawler or a browser's address bar
        $this->get('/api/users')->assertStatus(401)->assertJson(['message' => 'Unauthenticated.']);
        $this->get('/api/tasks', ['Accept' => 'text/html'])->assertStatus(401);
        $this->post('/api/logout')->assertStatus(401);
        $this->getJson('/api/users', ['Authorization' => 'Bearer not-a-real-token'])->assertStatus(401);
    }

    public function test_a_disabled_account_can_not_use_an_old_token_on_any_route(): void
    {
        $user = $this->makeUser('reader');
        $token = $user->createToken('t')->plainTextToken;
        $user->update(['is_active' => false]);

        $this->withToken($token)->getJson('/api/me')->assertForbidden();
    }
}
