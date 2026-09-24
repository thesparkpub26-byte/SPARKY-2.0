<?php

namespace Tests\Feature;

use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Concerns\MakesData;
use Tests\TestCase;

class ReaderEngagementTest extends TestCase
{
    use MakesData, RefreshDatabase;

    public function test_liking_needs_an_account_and_counts_once_per_reader(): void
    {
        $article = $this->makeArticle();
        $this->postJson("/api/reader/articles/{$article->id}/like")->assertUnauthorized();

        $reader = $this->makeUser('reader');
        Sanctum::actingAs($reader);
        $this->postJson("/api/reader/articles/{$article->id}/like")->assertOk()->assertJsonPath('likes_count', 1)->assertJsonPath('liked', true);
        $this->postJson("/api/reader/articles/{$article->id}/like")->assertJsonPath('likes_count', 1);

        Sanctum::actingAs($this->makeUser('reader'));
        $this->postJson("/api/reader/articles/{$article->id}/like")->assertJsonPath('likes_count', 2);

        Sanctum::actingAs($reader);
        $this->deleteJson("/api/reader/articles/{$article->id}/like")->assertJsonPath('likes_count', 1)->assertJsonPath('liked', false);
    }

    public function test_the_public_article_shows_likes_and_knows_who_liked_it(): void
    {
        $article = $this->makeArticle();
        $reader = $this->makeUser('reader');
        $token = $reader->createToken('t')->plainTextToken;
        $this->withToken($token)->postJson("/api/reader/articles/{$article->id}/like")->assertOk();
        $this->flushHeaders();
        $this->app['auth']->forgetGuards();

        $this->getJson("/api/reader/articles/{$article->id}")->assertJsonPath('likes_count', 1)->assertJsonPath('liked', false);
        $this->app['auth']->forgetGuards();
        $this->withToken($token)->getJson("/api/reader/articles/{$article->id}")->assertJsonPath('liked', true);
    }

    public function test_saved_articles(): void
    {
        $first = $this->makeArticle(['title' => 'First']);
        $second = $this->makeArticle(['title' => 'Second']);
        $draft = $this->makeArticle(['title' => 'Draft', 'status' => 'draft']);
        Sanctum::actingAs($this->makeUser('reader'));

        $this->postJson("/api/reader/articles/{$first->id}/bookmark")->assertOk()->assertJsonPath('bookmarked', true);
        $this->postJson("/api/reader/articles/{$second->id}/bookmark")->assertOk();
        $this->postJson("/api/reader/articles/{$draft->id}/bookmark")->assertNotFound(); // unpublished can't be saved

        $this->assertSame(['Second', 'First'], collect($this->getJson('/api/reader/bookmarks')->assertOk()->json('data'))->pluck('title')->all());

        $this->deleteJson("/api/reader/articles/{$second->id}/bookmark")->assertOk();
        $this->assertSame(1, $this->getJson('/api/reader/bookmarks')->json('total'));
    }

    public function test_one_readers_saved_list_is_private(): void
    {
        $article = $this->makeArticle();
        Sanctum::actingAs($this->makeUser('reader'));
        $this->postJson("/api/reader/articles/{$article->id}/bookmark");

        Sanctum::actingAs($this->makeUser('reader'));
        $this->assertSame(0, $this->getJson('/api/reader/bookmarks')->json('total'));
    }

    public function test_reporting_a_comment_notifies_the_eic_once(): void
    {
        $article = $this->makeArticle();
        $author = $this->makeUser('reader');
        $comment = $article->comments()->create(['user_id' => $author->id, 'body' => 'spammy']);
        $eic = $this->makeUser('eic');
        $reporter = $this->makeUser('reader');

        Sanctum::actingAs($author);
        $this->postJson("/api/reader/comments/{$comment->id}/report", ['reason' => 'spam'])->assertStatus(422); // not your own

        Sanctum::actingAs($reporter);
        $this->postJson("/api/reader/comments/{$comment->id}/report", ['reason' => 'nonsense'])->assertStatus(422);
        $this->postJson("/api/reader/comments/{$comment->id}/report", ['reason' => 'spam'])->assertOk();
        $this->postJson("/api/reader/comments/{$comment->id}/report", ['reason' => 'spam'])->assertOk();

        $this->assertSame(1, Notification::where('user_id', $eic->id)->where('title', 'Comment reported')->count());
    }

    public function test_sitemap_and_robots(): void
    {
        $live = $this->makeArticle();
        $draft = $this->makeArticle(['status' => 'draft']);

        $sitemap = $this->get('/sitemap.xml')->assertOk()->getContent();
        $this->assertStringContainsString("/article/{$live->id}", $sitemap);
        $this->assertStringNotContainsString("/article/{$draft->id}", $sitemap);

        $this->get('/robots.txt')->assertOk()->assertSee('Disallow: /admin', false)->assertSee('Sitemap:', false);
    }

    public function test_unpublished_articles_get_no_link_preview_details(): void
    {
        $draft = $this->makeArticle(['title' => 'Secret draft', 'status' => 'draft']);

        $this->assertStringNotContainsString('Secret draft', $this->get("/article/{$draft->id}")->getContent());
    }
}
