<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\Concerns\MakesData;
use Tests\TestCase;

/** Handing the Editor-in-Chief position over, and an Editor-in-Chief who is also a section editor. */
class EditorInChiefTest extends TestCase
{
    use MakesData, RefreshDatabase;

    /** Unique addresses: earlier test classes may have left committed users behind (MySQL commits on schema changes). */
    private function person(string $role, array $attributes = []): User
    {
        return $this->makeUser($role, array_merge(['email' => uniqid($role . '.') . '@example.test'], $attributes));
    }

    public function test_the_eic_promotes_the_next_eic_who_then_deactivates_the_old_one(): void
    {
        $old = $this->person('eic');
        $next = $this->person('staff_writer', ['secondary_role' => 'News Writer']);

        Sanctum::actingAs($old);
        $this->putJson("/api/users/{$next->id}", ['role' => 'eic', 'secondary_role' => 'News Editor'])
            ->assertOk()->assertJsonPath('role', 'eic');
        $this->assertTrue($next->fresh()->isEIC());
        $this->assertSame('You are now the Editor-in-Chief', Notification::where('user_id', $next->id)->value('title'));

        // The old Editor-in-Chief cannot step down alone...
        $this->putJson("/api/users/{$old->id}", ['is_active' => false])->assertForbidden();
        $this->putJson("/api/users/{$old->id}", ['role' => 'section_editor'])->assertForbidden();
        $this->deleteJson("/api/users/{$old->id}")->assertForbidden();
        $this->assertTrue($old->fresh()->isEIC() && $old->fresh()->is_active);

        // ...the new one does it
        Sanctum::actingAs($next->fresh());
        $this->putJson("/api/users/{$old->id}", ['is_active' => false])->assertOk();
        $this->assertFalse($old->fresh()->is_active);

        $again = $this->person('eic');
        $this->putJson("/api/users/{$again->id}", ['role' => 'section_editor', 'secondary_role' => 'News Editor'])->assertOk();
        $this->assertSame('section_editor', $again->fresh()->role);
    }

    public function test_the_site_always_keeps_an_active_eic(): void
    {
        $only = $this->person('eic');
        $admin = $this->person('admin');

        Sanctum::actingAs($admin);
        $this->putJson("/api/users/{$only->id}", ['is_active' => false])->assertStatus(422)
            ->assertJsonPath('message', 'There must always be an active Editor-in-Chief. Promote the next Editor-in-Chief first.');
        $this->putJson("/api/users/{$only->id}", ['role' => 'staff_writer'])->assertStatus(422);
        $this->deleteJson("/api/users/{$only->id}")->assertStatus(422);

        $this->putJson("/api/users/{$only->id}", ['name' => 'Renamed', 'is_active' => true])->assertOk();
    }

    public function test_an_eic_can_hold_section_editor_titles_and_keeps_the_eic_title(): void
    {
        $writer = $this->person('staff_writer');
        Sanctum::actingAs($this->person('eic'));

        $this->putJson("/api/users/{$writer->id}", [
            'role' => 'eic', 'secondary_role' => 'News Editor', 'tertiary_role' => 'Sports Editor',
        ])->assertOk();

        $eic = $writer->fresh();
        $this->assertSame('News Editor', $eic->secondary_role);
        $this->assertSame('Sports Editor', $eic->tertiary_role);
        $this->assertTrue($eic->isEditorInChiefAndSectionEditor());
        $this->assertSame('Editor-in-Chief', $eic->displayRole());
        $this->assertFalse($this->person('eic')->isEditorInChiefAndSectionEditor());
        $this->assertFalse($this->person('eic', ['secondary_role' => 'Copy Editor'])->isEditorInChiefAndSectionEditor());
    }

    public function test_a_submitted_article_notifies_an_eic_who_is_also_a_section_editor(): void
    {
        $writer = $this->person('staff_writer');
        $sectionEic = $this->person('eic', ['secondary_role' => 'News Editor']);
        $plainEic = $this->person('eic');
        $inactiveEic = $this->person('eic', ['secondary_role' => 'News Editor', 'is_active' => false]);
        $editor = $this->person('section_editor', ['secondary_role' => 'News Editor']);
        $article = $this->makeArticle(['author_id' => $writer->id, 'status' => Article::STATUS_DRAFT]);

        Sanctum::actingAs($writer);
        $this->postJson("/api/articles/{$article->id}/submit")->assertOk();

        $notified = Notification::where('type', Notification::TYPE_ARTICLE_SUBMITTED)->pluck('user_id')->all();
        $this->assertContains($editor->id, $notified);
        $this->assertContains($sectionEic->id, $notified);
        $this->assertNotContains($plainEic->id, $notified, 'a plain Editor-in-Chief is not a section editor');
        $this->assertNotContains($inactiveEic->id, $notified);
    }

    public function test_inactive_eics_are_not_notified_of_endorsements(): void
    {
        $activeEic = $this->person('eic');
        $inactiveEic = $this->person('eic', ['is_active' => false]);
        $copyreader = $this->person('staff_writer', ['secondary_role' => 'Copyreader']);
        $article = $this->makeArticle(['author_id' => $this->person('staff_writer')->id, 'status' => Article::STATUS_UNDER_REVIEW]);

        Sanctum::actingAs($copyreader);
        $this->postJson("/api/articles/{$article->id}/endorse")->assertOk();

        $notified = Notification::where('type', Notification::TYPE_ARTICLE_ENDORSED)->pluck('user_id')->all();
        $this->assertContains($activeEic->id, $notified);
        $this->assertNotContains($inactiveEic->id, $notified);
    }
}
