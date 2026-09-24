<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// A person's title can change (a News Writer becomes the News Editor next school year), but the work
// they did keeps the title they held at the time. So each article remembers its author's title,
// each task its assignee's, and each video credit its crew member's, as of when it was created.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('author_role', 120)->nullable();
        });
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('assignee_role', 120)->nullable();
        });
        Schema::table('article_credits', function (Blueprint $table) {
            $table->string('user_role', 120)->nullable();
        });

        // History was never recorded, so existing work gets the person's current title (the best we have)
        foreach (DB::table('users')->get(['id', 'role', 'secondary_role']) as $user) {
            $title = trim((string) $user->secondary_role) !== ''
                ? $user->secondary_role
                : ucwords(str_replace('_', ' ', (string) $user->role));

            DB::table('articles')->where('author_id', $user->id)->whereNull('author_role')->update(['author_role' => $title]);
            DB::table('tasks')->where('assignee_id', $user->id)->whereNull('assignee_role')->update(['assignee_role' => $title]);
            DB::table('article_credits')->where('user_id', $user->id)->whereNull('user_role')->update(['user_role' => $title]);
        }
    }

    public function down(): void
    {
        Schema::table('article_credits', function (Blueprint $table) {
            $table->dropColumn('user_role');
        });
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('assignee_role');
        });
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('author_role');
        });
    }
};
