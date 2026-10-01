<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Removes structure nothing in the system reads, writes or shows.
 *
 * Every column below was checked against the backend, the frontend and the data:
 *  - none is displayed or edited anywhere in the interface
 *  - the API accepted some of them, but no screen ever sent a value
 *  - all were empty (the one exception, users.bio, had two rows that no page could show or edit)
 *
 * Nothing here can be recovered by rolling back except the empty structure, so take a database backup
 * before running it on a live site (see DEPLOYMENT.md).
 */
return new class extends Migration
{
    /** table => columns */
    private const COLUMNS = [
        'users'                    => ['avatar', 'bio'],                       // profile pictures live in profile_picture
        'articles'                 => ['monitoring_sheet_url', 'eic_notes'],   // approval notes / sheet links: never entered
        'tasks'                    => ['monitoring_sheet_url', 'word_count_target'],
        'notifications'            => ['action_url'],                          // notifications carry their target in `data`
        'published_issues'         => ['flipbook_url'],                        // issues open from the uploaded PDF
        'monitoring_sheet_entries' => ['storage_url'],                         // file storage is tracked by has_files
    ];

    /** Drops the columns and tables nothing in the system uses. */
    public function up(): void
    {
        foreach (self::COLUMNS as $table => $columns) {
            $present = array_values(array_filter($columns, fn ($column) => Schema::hasColumn($table, $column)));
            if ($present) {
                Schema::table($table, fn (Blueprint $t) => $t->dropColumn($present));
            }
        }

        // Laravel's stock password-reset table: replaced by password_reset_codes
        Schema::dropIfExists('password_reset_tokens');

        // The site signs in with API tokens, never with cookie sessions
        Schema::dropIfExists('sessions');
    }

    /** Puts the dropped columns back (empty). */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable();
            $table->text('bio')->nullable();
        });
        Schema::table('articles', function (Blueprint $table) {
            $table->string('monitoring_sheet_url', 500)->nullable();
            $table->text('eic_notes')->nullable();
        });
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('monitoring_sheet_url', 500)->nullable();
            $table->unsignedInteger('word_count_target')->nullable();
        });
        Schema::table('notifications', fn (Blueprint $table) => $table->string('action_url')->nullable());
        Schema::table('published_issues', fn (Blueprint $table) => $table->string('flipbook_url')->nullable());
        Schema::table('monitoring_sheet_entries', fn (Blueprint $table) => $table->string('storage_url', 500)->nullable());

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }
};
