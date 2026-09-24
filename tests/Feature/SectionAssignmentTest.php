<?php

namespace Tests\Feature;

use App\Models\Section;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Concerns\MakesData;
use Tests\TestCase;

/** The section an article is assigned under follows it to the task, the article and the reader. */
class SectionAssignmentTest extends TestCase
{
    use MakesData, RefreshDatabase;

    /** Unique addresses: earlier test classes may have left committed users behind (MySQL commits on schema changes). */
    private function person(string $role)
    {
        return $this->makeUser($role, ['email' => uniqid($role . '.') . '@example.test']);
    }

    public function test_a_section_named_in_the_assignment_notes_becomes_the_tasks_real_section(): void
    {
        $eic = $this->person('eic');
        $writer = $this->person('staff_writer');
        $literary = $this->makeSection('Literary');

        Sanctum::actingAs($eic);
        $this->postJson('/api/tasks', [
            'title' => 'A poem', 'assignee_id' => $writer->id, 'type' => 'writing',
            'notes' => 'Section: Literary | Coverage: Poetry | Due Time: 10:00',
        ])->assertCreated()->assertJsonPath('section.name', 'Literary');

        $this->assertSame($literary->id, Task::firstOrFail()->section_id);
    }

    public function test_an_explicit_section_wins_over_the_notes_and_unknown_names_are_ignored(): void
    {
        $eic = $this->person('eic');
        $writer = $this->person('staff_writer');
        $news = $this->makeSection('News');
        $this->makeSection('Literary');

        Sanctum::actingAs($eic);
        $this->postJson('/api/tasks', ['title' => 'A', 'assignee_id' => $writer->id, 'section_id' => $news->id, 'notes' => 'Section: Literary'])
            ->assertJsonPath('section.name', 'News');
        $this->postJson('/api/tasks', ['title' => 'B', 'assignee_id' => $writer->id, 'notes' => 'Section: Nonsense'])
            ->assertJsonPath('section', null);
    }

    public function test_an_article_takes_the_section_of_the_task_it_is_written_for(): void
    {
        $writer = $this->person('staff_writer');
        $literary = $this->makeSection('Literary');
        $article = $this->makeArticle(['author_id' => $writer->id, 'section_id' => null, 'status' => 'draft']);

        $this->makeTask($writer, $this->person('eic'), ['article_id' => $article->id, 'notes' => 'Section: Literary']);

        $this->assertSame($literary->id, $article->fresh()->section_id);
    }

    public function test_an_article_that_already_has_a_section_keeps_it(): void
    {
        $writer = $this->person('staff_writer');
        $news = $this->makeSection('News');
        $this->makeSection('Literary');
        $article = $this->makeArticle(['author_id' => $writer->id, 'section_id' => $news->id]);

        $this->makeTask($writer, $this->person('eic'), ['article_id' => $article->id, 'notes' => 'Section: Literary']);

        $this->assertSame($news->id, $article->fresh()->section_id);
    }

    public function test_readers_see_the_section_on_a_published_article(): void
    {
        $writer = $this->person('staff_writer');
        $this->makeSection('Literary');
        $article = $this->makeArticle(['author_id' => $writer->id, 'section_id' => null]);
        $this->makeTask($writer, $this->person('eic'), ['article_id' => $article->id, 'notes' => 'Section: Literary']);

        $this->getJson("/api/reader/articles/{$article->id}")->assertJsonPath('category', 'Literary');
        $this->assertSame('Literary', $this->getJson('/api/reader/articles')->json('0.badge'));
    }

    public function test_section_names_resolve_with_their_aliases(): void
    {
        $sciTech = $this->makeSection('Sci-Tech');
        $feature = $this->makeSection('Feature');

        $this->assertSame($sciTech->id, Section::idForName('Sci&Tech'));
        $this->assertSame($feature->id, Section::idForName(' features '));
        $this->assertNull(Section::idForName(''));
        $this->assertNull(Section::idForName('Weather'));
    }
}
