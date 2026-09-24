<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\GalleryPhoto;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Tests\Concerns\MakesData;
use Tests\TestCase;

/**
 * Truncation (not a rolled-back transaction) on purpose: the database's full-text index only sees rows
 * that have been committed, and the search relies on it.
 */
class SearchTest extends TestCase
{
    use DatabaseTruncation, MakesData;

    private function search(array $query): array
    {
        return $this->getJson('/api/reader/search?' . http_build_query($query))->assertOk()->json();
    }

    private function titles(array $response): array
    {
        return collect($response['data'])->pluck('title')->all();
    }

    public function test_finds_by_stem_and_ranks_title_hits_first(): void
    {
        $this->makeArticle(['title' => 'Campus elections open', 'content' => '<p>Voting begins.</p>']);
        $this->makeArticle(['title' => 'Weekend weather', 'content' => '<p>The election results were announced.</p>']);
        $this->makeArticle(['title' => 'Unrelated', 'content' => '<p>Nothing to see.</p>']);

        $titles = $this->titles($this->search(['q' => 'election']));

        $this->assertSame(['Campus elections open', 'Weekend weather'], $titles);
    }

    public function test_related_words_and_filler_words(): void
    {
        $this->makeArticle(['title' => 'Quiz week schedule']);

        $this->assertContains('Quiz week schedule', $this->titles($this->search(['q' => 'exam'])));
        $this->assertContains('Quiz week schedule', $this->titles($this->search(['q' => 'the latest quiz'])));
    }

    public function test_a_misspelling_is_corrected_and_can_be_searched_as_typed(): void
    {
        $this->makeArticle(['title' => 'Students win robotics contest']);

        $fixed = $this->search(['q' => 'studnts']);
        $this->assertSame('students', $fixed['corrected_query']);
        $this->assertCount(1, $fixed['data']);

        $exact = $this->search(['q' => 'studnts', 'exact' => 1]);
        $this->assertNull($exact['corrected_query']);
        $this->assertSame(0, $exact['total']);
    }

    public function test_searches_articles_videos_and_photos_and_filters_by_type(): void
    {
        $this->makeArticle(['title' => 'Robotics article']);
        $this->makeArticle(['title' => 'Robotics video', 'type' => 'video', 'video_url' => 'https://youtu.be/abcdefghijk', 'video_category' => 'Reel']);
        $artist = $this->makeUser('staff_artist');
        GalleryPhoto::create(['title' => 'Robotics photo', 'image_path' => 'gallery/a.jpg', 'uploaded_by' => $artist->id, 'artist_id' => $artist->id]);

        $all = $this->search(['q' => 'robotics']);
        $this->assertSame(['article' => 1, 'video' => 1, 'photo' => 1], $all['kinds']);
        $this->assertSame(3, $all['total']);

        $this->assertSame(['Robotics video'], $this->titles($this->search(['q' => 'robotics', 'type' => 'videos'])));
        $this->assertSame(['Robotics photo'], $this->titles($this->search(['q' => 'robotics', 'type' => 'photos'])));
        $this->assertSame(['Robotics article'], $this->titles($this->search(['q' => 'robotics', 'type' => 'articles'])));

        $video = collect($all['data'])->firstWhere('kind', 'video');
        $this->assertSame('https://youtu.be/abcdefghijk', $video['video_url']);
    }

    public function test_unpublished_work_is_never_found(): void
    {
        $this->makeArticle(['title' => 'Secret budget draft', 'status' => 'draft']);
        $this->makeArticle(['title' => 'Secret scheduled', 'status' => 'scheduled', 'scheduled_at' => now()->addDay(), 'published_at' => null]);

        $this->assertSame(0, $this->search(['q' => 'secret'])['total']);
    }

    public function test_category_period_sort_and_paging(): void
    {
        $news = $this->makeSection('News');
        $sports = $this->makeSection('Sports');
        $this->makeArticle(['title' => 'Match one', 'section_id' => $sports->id, 'published_at' => now()->subDays(40)]);
        $this->makeArticle(['title' => 'Match two', 'section_id' => $sports->id, 'published_at' => now()->subDay()]);
        $this->makeArticle(['title' => 'Match report', 'section_id' => $news->id, 'published_at' => now()->subDays(2)]);

        $byCategory = $this->search(['q' => 'match', 'category' => 'Sports']);
        $this->assertSame(2, $byCategory['total']);
        $this->assertEqualsCanonicalizing([['name' => 'Sports', 'count' => 2], ['name' => 'News', 'count' => 1]], $this->search(['q' => 'match'])['categories']);

        $this->assertSame(['Match two', 'Match report'], $this->titles($this->search(['q' => 'match', 'period' => 'week', 'sort' => 'newest'])));
        $this->assertSame('Match one', $this->titles($this->search(['q' => 'match', 'sort' => 'oldest']))[0]);

        $page2 = $this->search(['q' => 'match', 'sort' => 'newest', 'per_page' => 2, 'page' => 2]);
        $this->assertSame(['Match one'], $this->titles($page2));
        $this->assertSame(2, $page2['last_page']);
    }

    public function test_very_short_and_empty_queries_return_nothing(): void
    {
        $this->makeArticle(['title' => 'A story']);

        $this->assertSame(0, $this->search(['q' => 'a'])['total']);
        $this->assertSame(0, $this->search([])['total']);
    }

    public function test_search_operators_in_the_query_do_nothing_special(): void
    {
        $this->makeArticle(['title' => 'Plain story']);

        $this->assertSame(200, $this->getJson('/api/reader/search?q=' . urlencode('+plain -story "x" (y) *'))->status());
    }
}
