<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Lets the reader search find words in a large archive without scanning every article. MySQL / MariaDB only. */
    public function up(): void
    {
        if (!in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        Schema::table('articles', function (Blueprint $table) {
            $table->fullText(['title', 'excerpt', 'content'], 'articles_fulltext');
        });
    }

    /** Removes the full-text search index from articles (MySQL / MariaDB only). */
    public function down(): void
    {
        if (!in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        Schema::table('articles', function (Blueprint $table) {
            $table->dropFullText('articles_fulltext');
        });
    }
};
