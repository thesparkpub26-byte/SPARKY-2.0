<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Concerns\MakesData;
use Tests\TestCase;

/** Who is allowed to do what. The dashboards hide buttons, but the API is what actually protects the data. */
class PermissionsTest extends TestCase
{
    use MakesData, RefreshDatabase;

    public function test_anonymous_visitors_cannot_use_staff_endpoints(): void
    {
        $this->getJson('/api/users')->assertUnauthorized();
        $this->getJson('/api/articles')->assertUnauthorized();
    }

    public function test_readers_are_locked_out_of_every_staff_endpoint(): void
    {
        Sanctum::actingAs($this->makeUser('reader'));
        $article = $this->makeArticle();

        foreach (['/api/users', '/api/sections', '/api/articles', '/api/tasks', '/api/notifications', '/api/gallery', '/api/admin/overview'] as $url) {
            $this->getJson($url)->assertForbidden();
        }
        $this->postJson('/api/users', ['name' => 'X', 'email' => 'x@example.test', 'password' => 'Sturdy-pass-88', 'role' => 'admin'])->assertForbidden();
        $this->postJson('/api/sections', ['name' => 'Hacked'])->assertForbidden();
        $this->postJson("/api/articles/{$article->id}/approve")->assertForbidden();
        $this->getJson('/api/me')->assertOk();
    }

    public function test_staff_can_look_people_up_but_not_manage_accounts_or_sections(): void
    {
        Sanctum::actingAs($this->makeUser('staff_writer'));

        $this->getJson('/api/users')->assertOk();
        $this->postJson('/api/users', ['name' => 'X', 'email' => 'x@example.test', 'password' => 'Sturdy-pass-88', 'role' => 'reader'])->assertForbidden();
        $this->postJson('/api/sections', ['name' => 'Hacked'])->assertForbidden();
    }

    public function test_readers_contact_details_are_only_listed_for_admin_and_eic(): void
    {
        $this->makeUser('reader', ['email' => 'private.reader@example.test']);

        Sanctum::actingAs($this->makeUser('staff_writer'));
        $this->assertStringNotContainsString('private.reader@example.test', $this->getJson('/api/users')->getContent());

        Sanctum::actingAs($this->makeUser('eic'));
        $this->assertStringContainsString('private.reader@example.test', $this->getJson('/api/users')->getContent());
    }

    public function test_only_admins_can_delete_users(): void
    {
        $target = $this->makeUser('staff_writer');

        Sanctum::actingAs($this->makeUser('eic'));
        $this->deleteJson("/api/users/{$target->id}")->assertForbidden();
        $this->assertNotNull($target->fresh());

        Sanctum::actingAs($this->makeUser('admin'));
        $this->deleteJson("/api/users/{$target->id}")->assertOk();
        $this->assertNull($target->fresh());
    }

    public function test_only_admins_manage_admin_accounts(): void
    {
        $admin = $this->makeUser('admin');
        $newUser = ['name' => 'T', 'email' => 't@example.test', 'password' => 'Sturdy-pass-88'];

        Sanctum::actingAs($this->makeUser('eic'));
        $this->postJson('/api/users', $newUser + ['role' => 'admin'])->assertForbidden();
        $this->putJson("/api/users/{$admin->id}", ['name' => 'Renamed'])->assertForbidden();
        $this->deleteJson("/api/users/{$admin->id}")->assertForbidden();
        $this->postJson('/api/users', $newUser + ['role' => 'reader'])->assertCreated();

        Sanctum::actingAs($admin);
        $this->postJson('/api/users', ['email' => 't2@example.test'] + $newUser + ['role' => 'admin'])->assertCreated();
    }

    public function test_article_workflow_rules(): void
    {
        $writer = $this->makeUser('staff_writer');
        $editor = $this->makeUser('section_editor');
        $eic = $this->makeUser('eic');
        $stranger = $this->makeUser('staff_writer');
        $copyreader = $this->makeUser('staff_writer', ['secondary_role' => 'Copyreader']);
        $draft = $this->makeArticle(['author_id' => $writer->id, 'status' => Article::STATUS_DRAFT]);

        Sanctum::actingAs($stranger);
        $this->putJson("/api/articles/{$draft->id}", ['title' => 'Pwned'])->assertForbidden();
        $this->postJson("/api/articles/{$draft->id}/endorse")->assertForbidden();

        Sanctum::actingAs($writer);
        $this->putJson("/api/articles/{$draft->id}", ['title' => 'Mine', 'status' => 'draft'])->assertOk();
        $this->postJson("/api/articles/{$draft->id}/submit")->assertOk();
        $this->putJson("/api/articles/{$draft->id}", ['status' => 'published'])->assertForbidden();

        Sanctum::actingAs($editor);
        $this->putJson("/api/articles/{$draft->id}", ['status' => 'published'])->assertForbidden();
        $this->postJson("/api/articles/{$draft->id}/endorse")->assertOk();
        $this->postJson("/api/articles/{$draft->id}/approve")->assertForbidden();

        Sanctum::actingAs($copyreader);
        $this->postJson("/api/articles/{$draft->id}/endorse")->assertOk();

        Sanctum::actingAs($eic);
        $this->putJson("/api/articles/{$draft->id}", ['status' => 'published'])->assertOk();
        $this->assertSame('published', $draft->fresh()->status);
    }

    public function test_task_rules(): void
    {
        $editor = $this->makeUser('section_editor');
        $writer = $this->makeUser('staff_writer');
        $other = $this->makeUser('staff_writer');
        $task = $this->makeTask($writer, $editor);

        Sanctum::actingAs($other);
        $this->postJson("/api/tasks/{$task->id}/complete")->assertForbidden();
        $this->deleteJson("/api/tasks/{$task->id}")->assertForbidden();
        $this->postJson("/api/tasks/{$task->id}/submit")->assertForbidden();
        // linking a crew task to an article is something staff do for each other
        $this->putJson("/api/tasks/{$task->id}", ['notes' => 'linked'])->assertOk();
        $this->putJson("/api/tasks/{$task->id}", ['assignee_id' => $other->id])->assertForbidden();

        Sanctum::actingAs($writer);
        $this->postJson("/api/tasks/{$task->id}/submit")->assertOk();

        Sanctum::actingAs($editor);
        $this->postJson("/api/tasks/{$task->id}/complete")->assertOk();
    }

    public function test_academic_years_and_monitoring_tasks_are_for_editors(): void
    {
        Sanctum::actingAs($this->makeUser('staff_writer'));
        $this->postJson('/api/press-works', ['academic_year' => '2098-2099'])->assertForbidden();
        $this->deleteJson('/api/press-works/2098-2099')->assertForbidden();

        Sanctum::actingAs($this->makeUser('section_editor'));
        $this->postJson('/api/press-works', ['academic_year' => '2098-2099'])->assertCreated();
    }

    public function test_deactivated_accounts_are_refused_even_with_a_valid_token(): void
    {
        $inactive = $this->makeUser('section_editor', ['is_active' => false]);
        $token = $inactive->createToken('t')->plainTextToken;

        $this->withToken($token)->getJson('/api/tasks')->assertForbidden();
        $this->assertSame(0, $inactive->tokens()->count(), 'the stale token is revoked');
    }

    public function test_publishing_scheduled_articles(): void
    {
        $due = $this->makeArticle(['status' => Article::STATUS_SCHEDULED, 'scheduled_at' => now()->subMinute(), 'published_at' => null]);
        $later = $this->makeArticle(['status' => Article::STATUS_SCHEDULED, 'scheduled_at' => now()->addDay(), 'published_at' => null]);

        $this->artisan('articles:publish-scheduled')->assertSuccessful();

        $this->assertSame('published', $due->fresh()->status);
        $this->assertSame('scheduled', $later->fresh()->status);
    }
}
