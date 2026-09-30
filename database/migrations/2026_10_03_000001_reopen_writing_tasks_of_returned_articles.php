<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * An article returned after the Section Editor had sent it on used to leave the writer's task "completed", so it sat
 * under Submitted and could not be revised. New returns reopen the task; this fixes the ones already stuck.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('tasks')
            ->where('type', 'writing')
            ->where('status', 'completed')
            ->whereIn('article_id', DB::table('articles')->select('id')->where('status', 'rejected'))
            ->update(['status' => 'returned', 'completed_at' => null]);
    }

    public function down(): void
    {
        // Not reversible: it is not known which tasks were stuck
    }
};
