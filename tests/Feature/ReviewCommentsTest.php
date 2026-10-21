<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ArticleVersion;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Concerns\MakesData;
use Tests\TestCase;

/** Reviewers highlight text and comment, the writer marks the comments done, and the article keeps its history. */
class ReviewCommentsTest extends TestCase
{
    use MakesData, RefreshDatabase;

    /** Unique addresses: earlier test classes may have left committed users behind (MySQL commits on schema changes). */
    private function person(string $role, array $attributes = []): User
    {
        return $this->makeUser($role, array_merge(['email' => uniqid($role . '.') . '@example.test'], $attributes));
    }

    private function commentOn(Article $article, array $attributes = []): array
    {
        return array_merge([
            'quote'        => 'Body text',
            'start_offset' => 0,
            'end_offset'   => 9,
            'body'         => 'Please add more life to this part.',
        ], $attributes);
    }

    public function test_a_reviewer_comments_on_a_highlight_and_the_writer_sees_it(): void
    {
        $writer = $this->person('staff_writer');
        $editor = $this->person('section_editor');
        $article = $this->makeArticle(['author_id' => $writer->id, 'status' => Article::STATUS_SUBMITTED]);

        Sanctum::actingAs($editor);
        $this->postJson("/api/articles/{$article->id}/review-comments", $this->commentOn($article))
            ->assertCreated()
            ->assertJsonPath('status', 'open')
            ->assertJsonPath('author_role', 'section_editor')
            ->assertJsonPath('quote', 'Body text')
            ->assertJsonPath('user.name', $editor->name);

        Sanctum::actingAs($writer);
        $this->getJson("/api/articles/{$article->id}/review-comments")
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.body', 'Please add more life to this part.');
    }

    public function test_a_copyreader_and_the_eic_can_comment_too(): void
    {
        $article = $this->makeArticle(['status' => Article::STATUS_SUBMITTED]);
        $copyreader = $this->person('staff_writer', ['secondary_role' => 'Copyreader']);
        $eic = $this->person('eic');

        Sanctum::actingAs($copyreader);
        $this->postJson("/api/articles/{$article->id}/review-comments", $this->commentOn($article))
            ->assertCreated()->assertJsonPath('author_role', 'copyreader');

        Sanctum::actingAs($eic);
        $this->postJson("/api/articles/{$article->id}/review-comments", $this->commentOn($article))
            ->assertCreated()->assertJsonPath('author_role', 'eic');
    }

    public function test_the_writer_and_outsiders_cannot_comment_and_readers_are_turned_away(): void
    {
        $writer = $this->person('staff_writer');
        $article = $this->makeArticle(['author_id' => $writer->id]);

        Sanctum::actingAs($writer);
        $this->postJson("/api/articles/{$article->id}/review-comments", $this->commentOn($article))->assertForbidden();

        Sanctum::actingAs($this->person('reader'));
        $this->postJson("/api/articles/{$article->id}/review-comments", $this->commentOn($article))->assertForbidden();
        $this->getJson("/api/articles/{$article->id}/review-comments")->assertForbidden();

        // Another writer has no business reading this article's review
        Sanctum::actingAs($this->person('staff_writer'));
        $this->getJson("/api/articles/{$article->id}/review-comments")->assertForbidden();
        $this->getJson("/api/articles/{$article->id}/review-details")->assertForbidden();
    }

    public function test_a_comment_needs_a_highlight_and_a_note(): void
    {
        $article = $this->makeArticle();
        Sanctum::actingAs($this->person('section_editor'));

        $this->postJson("/api/articles/{$article->id}/review-comments", ['quote' => 'Body'])->assertStatus(422)->assertJsonValidationErrors('body');
        $this->postJson("/api/articles/{$article->id}/review-comments", ['body' => 'Fix this'])->assertStatus(422)->assertJsonValidationErrors('quote');
        $this->postJson("/api/articles/{$article->id}/review-comments", $this->commentOn($article, ['body' => str_repeat('a', 1001)]))
            ->assertStatus(422)->assertJsonValidationErrors('body');
    }

    public function test_the_writer_marks_a_comment_done_and_the_reviewer_can_reopen_it(): void
    {
        $writer = $this->person('staff_writer');
        $editor = $this->person('section_editor');
        $article = $this->makeArticle(['author_id' => $writer->id, 'status' => Article::STATUS_REJECTED]);

        Sanctum::actingAs($editor);
        $id = $this->postJson("/api/articles/{$article->id}/review-comments", $this->commentOn($article))->json('id');

        Sanctum::actingAs($writer);
        $this->patchJson("/api/review-comments/{$id}", ['status' => 'resolved'])
            ->assertOk()
            ->assertJsonPath('status', 'resolved')
            ->assertJsonPath('resolver.name', $writer->name);
        $this->assertNotNull(\App\Models\ReviewComment::find($id)->resolved_at);

        Sanctum::actingAs($editor);
        $this->patchJson("/api/review-comments/{$id}", ['status' => 'open'])
            ->assertOk()->assertJsonPath('status', 'open')->assertJsonPath('resolver', null);

        $this->patchJson("/api/review-comments/{$id}", ['status' => 'maybe'])->assertStatus(422);

        // Someone with no part in the article cannot touch it
        Sanctum::actingAs($this->person('staff_writer'));
        $this->patchJson("/api/review-comments/{$id}", ['status' => 'resolved'])->assertForbidden();
    }

    public function test_only_the_author_deletes_a_comment_and_only_while_it_is_open(): void
    {
        $writer = $this->person('staff_writer');
        $editor = $this->person('section_editor');
        $other = $this->person('section_editor');
        $article = $this->makeArticle(['author_id' => $writer->id]);

        Sanctum::actingAs($editor);
        $id = $this->postJson("/api/articles/{$article->id}/review-comments", $this->commentOn($article))->json('id');

        Sanctum::actingAs($other);
        $this->deleteJson("/api/review-comments/{$id}")->assertForbidden();

        Sanctum::actingAs($writer);
        $this->patchJson("/api/review-comments/{$id}", ['status' => 'resolved'])->assertOk();

        Sanctum::actingAs($editor);
        $this->deleteJson("/api/review-comments/{$id}")->assertStatus(422);
        $this->patchJson("/api/review-comments/{$id}", ['status' => 'open'])->assertOk();
        $this->deleteJson("/api/review-comments/{$id}")->assertOk();
        $this->assertDatabaseMissing('review_comments', ['id' => $id]);
    }

    public function test_submitting_and_a_reviewers_edit_build_the_version_history(): void
    {
        $writer = $this->person('staff_writer');
        $editor = $this->person('section_editor');
        $article = $this->makeArticle([
            'author_id'   => $writer->id,
            'status'      => Article::STATUS_DRAFT,
            'content'     => '<p>First draft.</p>',
            'cover_image' => '/storage/cover.jpg',
            'media_files' => ['/storage/one.jpg'],
        ]);

        Sanctum::actingAs($writer);
        $this->postJson("/api/articles/{$article->id}/submit")->assertOk();
        // Submitting again without changing anything adds no version
        $this->postJson("/api/articles/{$article->id}/submit")->assertOk();
        $this->assertSame(1, $article->versions()->count());

        Sanctum::actingAs($editor);
        $this->putJson("/api/articles/{$article->id}", ['content' => '<p>Tightened draft.</p>'])->assertOk();
        // Saving the same text again is not a new version
        $this->putJson("/api/articles/{$article->id}", ['content' => '<p>Tightened draft.</p>'])->assertOk();

        $versions = $article->versions()->orderBy('version')->get();
        $this->assertSame([1, 2], $versions->pluck('version')->all());
        $this->assertSame(['Submitted for review', 'Revised article content'], $versions->pluck('label')->all());
        $this->assertSame([$writer->id, $editor->id], $versions->pluck('user_id')->all());
        $this->assertSame('section_editor', $versions[1]->author_role);
        $this->assertSame('<p>First draft.</p>', $versions[0]->content);

        // The writer editing their own text does not create versions: only submitting does
        Sanctum::actingAs($writer);
        $this->putJson("/api/articles/{$article->id}", ['content' => '<p>Writer tweak.</p>'])->assertOk();
        $this->assertSame(2, $article->versions()->count());
    }

    public function test_a_reviewer_edit_saves_the_writers_text_first_when_nothing_was_submitted(): void
    {
        $writer = $this->person('staff_writer');
        $article = $this->makeArticle(['author_id' => $writer->id, 'status' => Article::STATUS_DRAFT, 'content' => '<p>Original.</p>']);

        Sanctum::actingAs($this->person('section_editor'));
        $this->putJson("/api/articles/{$article->id}", ['content' => '<p>Edited.</p>'])->assertOk();

        $versions = $article->versions()->orderBy('version')->get();
        $this->assertSame(['Draft', 'Revised article content'], $versions->pluck('label')->all());
        $this->assertSame('<p>Original.</p>', $versions[0]->content);
        $this->assertSame($writer->id, $versions[0]->user_id);
    }

    public function test_the_details_list_the_activity_and_the_versions_and_a_version_can_be_previewed(): void
    {
        $writer = $this->person('staff_writer');
        $editor = $this->person('section_editor');
        $article = $this->makeArticle([
            'author_id'   => $writer->id,
            'status'      => Article::STATUS_DRAFT,
            'content'     => '<p>First draft.</p>',
            'cover_image' => '/storage/cover.jpg',
            'media_files' => ['/storage/one.jpg'],
        ]);
        $task = $this->makeTask($writer, $editor, ['article_id' => $article->id]);

        Sanctum::actingAs($writer);
        $this->postJson("/api/articles/{$article->id}/submit")->assertOk();

        Sanctum::actingAs($editor);
        $this->putJson("/api/articles/{$article->id}", ['content' => '<p>Tightened draft.</p>'])->assertOk();
        $this->postJson("/api/articles/{$article->id}/review-comments", $this->commentOn($article))->assertCreated();
        $this->postJson("/api/tasks/{$task->id}/return", ['notes' => 'See the comments.'])->assertOk();

        Sanctum::actingAs($writer);
        $details = $this->getJson("/api/articles/{$article->id}/review-details")->assertOk();

        $details->assertJsonCount(2, 'versions')
            ->assertJsonPath('versions.0.version', 2)
            ->assertJsonPath('versions.0.is_latest', true)
            ->assertJsonPath('versions.0.label', 'Revised article content')
            ->assertJsonPath('versions.0.user', $editor->name)
            ->assertJsonPath('versions.1.is_latest', false);

        $actions = collect($details->json('activities'))->pluck('action')->all();
        $this->assertContains('Submitted for Review', $actions);
        $this->assertContains('Left a Comment', $actions);
        $this->assertContains('Returned for Revision', $actions);
        // Plain saves are noise, not history
        $this->assertNotContains('Updated an article', $actions);
        $this->assertSame('Article Content', collect($details->json('activities'))->firstWhere('action', 'Left a Comment')['section']);

        $firstVersion = $article->versions()->where('version', 1)->first();
        $this->getJson("/api/articles/{$article->id}/versions/{$firstVersion->id}")
            ->assertOk()->assertJsonPath('content', '<p>First draft.</p>')->assertJsonPath('user.name', $writer->name);

        // A version of some other article is not reachable through this one
        $other = $this->makeArticle(['author_id' => $writer->id]);
        $foreign = ArticleVersion::record($other, $writer, 'Submitted for review');
        $this->getJson("/api/articles/{$article->id}/versions/{$foreign->id}")->assertNotFound();
    }

    public function test_comments_and_versions_go_away_with_the_article(): void
    {
        $article = $this->makeArticle();
        Sanctum::actingAs($this->person('section_editor'));
        $this->postJson("/api/articles/{$article->id}/review-comments", $this->commentOn($article))->assertCreated();
        ArticleVersion::record($article, null, 'Submitted for review');

        $article->delete();

        $this->assertDatabaseCount('review_comments', 0);
        $this->assertDatabaseCount('article_versions', 0);
    }
}
