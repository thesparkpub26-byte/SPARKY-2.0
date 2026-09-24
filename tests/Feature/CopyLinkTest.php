<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\MakesData;
use Tests\TestCase;

/** Copying an article's link: the share count, the preview people get when they paste it, and running behind a proxy. */
class CopyLinkTest extends TestCase
{
    use MakesData, RefreshDatabase;

    public function test_a_visitor_is_counted_once_per_article_per_day_however_often_they_copy_the_link(): void
    {
        $article = $this->makeArticle();

        $this->postJson("/api/reader/articles/{$article->id}/share")->assertOk()->assertJson(['shares' => 1]);
        $this->postJson("/api/reader/articles/{$article->id}/share")->assertOk()->assertJson(['shares' => 1]);
        $this->postJson("/api/reader/articles/{$article->id}/share")->assertOk()->assertJson(['shares' => 1]);
        $this->assertSame(1, (int) $article->fresh()->shares_count);

        // another person (another address) adds one
        $this->withServerVariables(['REMOTE_ADDR' => '198.51.100.7'])->postJson("/api/reader/articles/{$article->id}/share")->assertJson(['shares' => 2]);
        $this->assertSame(2, (int) $article->fresh()->shares_count);

        // and the same visitor on another article counts there
        $other = $this->makeArticle();
        $this->postJson("/api/reader/articles/{$other->id}/share")->assertJson(['shares' => 1]);
    }

    public function test_reading_and_sharing_do_not_make_an_article_look_edited(): void
    {
        $article = $this->makeArticle();
        DB::table('articles')->where('id', $article->id)->update(['updated_at' => '2024-01-02 03:04:05']);

        $this->postJson("/api/reader/articles/{$article->id}/read")->assertOk()->assertJson(['reads' => 1]);
        $this->postJson("/api/reader/articles/{$article->id}/share")->assertOk();

        $fresh = $article->fresh();
        $this->assertSame(1, (int) $fresh->reads_count);
        $this->assertSame(1, (int) $fresh->shares_count);
        $this->assertSame('2024-01-02 03:04:05', $fresh->updated_at->format('Y-m-d H:i:s'), 'the sitemap reports updated_at as "last modified"');
    }

    public function test_only_readable_articles_can_be_shared(): void
    {
        $draft = $this->makeArticle(['status' => 'draft']);

        $this->postJson("/api/reader/articles/{$draft->id}/share")->assertNotFound();
        $this->assertSame(0, (int) $draft->fresh()->shares_count);
    }

    public function test_the_copied_link_shows_a_title_and_picture_when_pasted_into_a_chat(): void
    {
        $article = $this->makeArticle([
            'title'       => 'Robotics team wins regional',
            'excerpt'     => 'The CSPC robotics team took first place.',
            'cover_image' => '/storage/article-media/cover.jpg',
        ]);

        $html = $this->get("/article/{$article->id}")->assertOk()->getContent();

        $this->assertStringContainsString('<meta property="og:title" content="Robotics team wins regional | TheSPARK">', $html);
        $this->assertStringContainsString('content="The CSPC robotics team took first place."', $html);
        $this->assertStringContainsString('<meta property="og:image" content="http://localhost/storage/article-media/cover.jpg">', $html);
        $this->assertStringContainsString('<link rel="canonical" href="http://localhost/article/' . $article->id . '">', $html);
    }

    public function test_an_unpublished_article_leaks_nothing_through_its_link(): void
    {
        $draft = $this->makeArticle(['title' => 'Secret draft', 'status' => 'draft']);

        $html = $this->get("/article/{$draft->id}")->assertOk()->getContent();

        $this->assertStringNotContainsString('Secret draft', $html);
    }

    public function test_behind_a_proxy_each_visitor_is_told_apart_and_https_is_recognised(): void
    {
        $article = $this->makeArticle();
        $proxy = ['REMOTE_ADDR' => '10.0.0.1'];   // the host's load balancer; it forwards the real address

        $this->withServerVariables($proxy)->postJson("/api/reader/articles/{$article->id}/share", [], ['X-Forwarded-For' => '203.0.113.10'])->assertJson(['shares' => 1]);
        $this->withServerVariables($proxy)->postJson("/api/reader/articles/{$article->id}/share", [], ['X-Forwarded-For' => '203.0.113.11'])->assertJson(['shares' => 2]);

        $this->withServerVariables($proxy)->get('/', ['X-Forwarded-Proto' => 'https'])->assertHeader('Strict-Transport-Security');
    }
}
