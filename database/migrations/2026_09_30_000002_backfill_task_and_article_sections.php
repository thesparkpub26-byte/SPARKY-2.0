<?php

use App\Models\Section;
use App\Support\PublicCache;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Tasks used to keep their section only as text in the notes ("Section: Literary | ..."), so the tasks, and
 * the articles written for them, ended up with no real section and showed as "Unassigned" outside the
 * Editor-in-Chief's screen. This gives them the section they were assigned under.
 */
return new class extends Migration
{
    /**
     * Fills in missing sections on tasks and articles: from the task's notes, from the article's other tasks, or
     * from the writer's title.
     */
    public function up(): void
    {
        // 1. A task's own notes
        DB::table('tasks')->whereNull('section_id')->whereNotNull('notes')->get(['id', 'notes'])
            ->each(function ($task) {
                if ($sectionId = Section::idFromNotes($task->notes)) {
                    DB::table('tasks')->where('id', $task->id)->update(['section_id' => $sectionId]);
                }
            });

        // 2. The other tasks on the same article (a submitted task's notes no longer say the section)
        $this->sectionsByArticle()->each(function ($sectionId, $articleId) {
            DB::table('tasks')->where('article_id', $articleId)->whereNull('section_id')->update(['section_id' => $sectionId]);
            DB::table('articles')->where('id', $articleId)->whereNull('section_id')->where('type', '!=', 'video')->update(['section_id' => $sectionId]);
        });

        // 3. Articles no task could place: the writer's title at the time ("Literary Writer" -> Literary)
        DB::table('articles')->whereNull('section_id')->where('type', '!=', 'video')->get(['id', 'author_role'])
            ->each(function ($article) {
                $name = trim(preg_replace('/\s*(Writer|Editor|Artist|Presenter)$/i', '', (string) $article->author_role));

                if ($sectionId = Section::idForName($name)) {
                    DB::table('articles')->where('id', $article->id)->update(['section_id' => $sectionId]);
                }
            });

        PublicCache::forget('articles');
    }

    /** Does nothing: sections were only filled in where they were missing. */
    public function down(): void
    {
        // Nothing to undo: the sections were only filled in where they were missing.
    }

    /** article id => the section of its earliest task that has one */
    private function sectionsByArticle()
    {
        return DB::table('tasks')->whereNotNull('article_id')->whereNotNull('section_id')
            ->orderBy('id')->get(['article_id', 'section_id'])
            ->unique('article_id')->pluck('section_id', 'article_id');
    }
};
