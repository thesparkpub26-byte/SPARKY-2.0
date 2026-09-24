<?php

namespace Tests\Concerns;

use App\Models\Article;
use App\Models\Section;
use App\Models\Task;
use App\Models\User;

/** Small builders so each test can say what it needs in one line. */
trait MakesData
{
    private int $counter = 0;

    protected function makeUser(string $role = 'reader', array $attributes = []): User
    {
        $this->counter++;

        return User::create(array_merge([
            'name'      => ucfirst($role) . " {$this->counter}",
            'email'     => "{$role}{$this->counter}@example.test",
            'password'  => 'password123',
            'role'      => $role,
            'is_active' => true,
        ], $attributes));
    }

    protected function makeSection(string $name = 'News'): Section
    {
        return Section::firstOrCreate(['name' => $name]);
    }

    protected function makeArticle(array $attributes = []): Article
    {
        $this->counter++;

        return Article::create(array_merge([
            'title'        => "Story {$this->counter}",
            'content'      => '<p>Body text of the story.</p>',
            'author_id'    => $attributes['author_id'] ?? $this->makeUser('staff_writer')->id,
            'section_id'   => $this->makeSection()->id,
            'type'         => Article::TYPE_ARTICLE,
            'status'       => Article::STATUS_PUBLISHED,
            'published_at' => now()->subDay(),
        ], $attributes));
    }

    protected function makeTask(User $assignee, User $assignedBy, array $attributes = []): Task
    {
        return Task::create(array_merge([
            'title'       => 'A task',
            'assignee_id' => $assignee->id,
            'assigned_by' => $assignedBy->id,
            'type'        => Task::TYPE_WRITING,
            'status'      => Task::STATUS_PENDING,
        ], $attributes));
    }
}
