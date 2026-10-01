<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Indexes for the lists the site reads most. Each one matches a query the app really runs:
     *  - articles: the reader lists (published, not a video, newest first) and the "is anything scheduled
     *    and due?" check that runs before each of them, plus the dashboards' counts by status
     *  - gallery / issues: newest first
     *  - notifications: a person's newest, and their unread ones
     *  - article_comments: an article's newest comments
     *  - tasks: a person's tasks by status, and the newest first
     */
    private const INDEXES = [
        'articles' => [
            'articles_status_type_published_idx' => ['status', 'type', 'published_at'],
            'articles_status_scheduled_idx'      => ['status', 'scheduled_at'],
        ],
        'gallery_photos'    => ['gallery_photos_created_idx' => ['created_at']],
        'published_issues'  => ['published_issues_created_idx' => ['created_at']],
        'notifications' => [
            'notifications_user_created_idx' => ['user_id', 'created_at'],
            'notifications_user_read_idx'    => ['user_id', 'read_at'],
        ],
        'article_comments' => ['article_comments_article_created_idx' => ['article_id', 'created_at']],
        'tasks' => [
            'tasks_assignee_status_idx' => ['assignee_id', 'status'],
            'tasks_created_idx'         => ['created_at'],
        ],
    ];

    /** Adds database indexes that speed up the common lists and searches. */
    public function up(): void
    {
        foreach (self::INDEXES as $table => $indexes) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            foreach ($indexes as $name => $columns) {
                if (Schema::hasIndex($table, $name) || !Schema::hasColumns($table, $columns)) {
                    continue;
                }

                Schema::table($table, fn (Blueprint $t) => $t->index($columns, $name));
            }
        }
    }

    /** Removes those indexes. */
    public function down(): void
    {
        foreach (self::INDEXES as $table => $indexes) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            foreach (array_keys($indexes) as $name) {
                if (Schema::hasIndex($table, $name)) {
                    Schema::table($table, fn (Blueprint $t) => $t->dropIndex($name));
                }
            }
        }
    }
};
