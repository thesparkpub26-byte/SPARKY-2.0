<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /** Adds activity log entries for users, articles and tasks that existed before the log did. */
    public function up(): void
    {
        $activities = [];

        foreach (DB::table('users')->select('id', 'name', 'created_at')->whereNotNull('created_at')->get() as $user) {
            $activities[] = [
                'actor_id' => null,
                'action' => 'User account created',
                'subject_type' => 'App\\Models\\User',
                'subject_id' => $user->id,
                'subject_label' => $user->name,
                'created_at' => $user->created_at,
            ];
        }

        foreach (DB::table('articles')->select('id', 'author_id', 'title', 'created_at')->whereNotNull('created_at')->get() as $article) {
            $activities[] = [
                'actor_id' => $article->author_id,
                'action' => 'Article created',
                'subject_type' => 'App\\Models\\Article',
                'subject_id' => $article->id,
                'subject_label' => $article->title,
                'created_at' => $article->created_at,
            ];
        }

        foreach (DB::table('tasks')->select('id', 'assigned_by', 'title', 'created_at')->whereNotNull('created_at')->get() as $task) {
            $activities[] = [
                'actor_id' => $task->assigned_by,
                'action' => 'Task assigned',
                'subject_type' => 'App\\Models\\Task',
                'subject_id' => $task->id,
                'subject_label' => $task->title,
                'created_at' => $task->created_at,
            ];
        }

        foreach (array_chunk($activities, 500) as $batch) {
            DB::table('activities')->insert($batch);
        }
    }

    /** Removes the entries that were backfilled. */
    public function down(): void
    {
        DB::table('activities')
            ->whereIn('action', ['User account created', 'Article created', 'Task assigned'])
            ->delete();
    }
};
