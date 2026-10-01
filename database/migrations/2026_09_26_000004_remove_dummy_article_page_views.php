<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

// The old static demo article page ("CSPC Launches New Student Portal") logged page views against
// the bare /article path with no article attached. Real article views always carry an article id.
return new class extends Migration
{
    /** Deletes the demo page-view rows that pointed at a placeholder article page. */
    public function up(): void
    {
        DB::table('page_views')->whereNull('article_id')->where('page_path', '/article')->delete();
    }

    /** Does nothing: the deleted demo rows are not restored. */
    public function down(): void
    {
        // The deleted demo rows are not restored.
    }
};
