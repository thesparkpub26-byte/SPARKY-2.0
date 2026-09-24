<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            if (!Schema::hasColumn('articles', 'scheduled_at')) {
                $table->timestamp('scheduled_at')->nullable()->after('rejected_at');
            }
            if (!Schema::hasColumn('articles', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('scheduled_at');
            }
        });

        DB::statement("ALTER TABLE articles MODIFY status ENUM('draft','submitted','under_review','endorsed','approved','rejected','published','scheduled') DEFAULT 'draft'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE articles MODIFY status ENUM('draft','submitted','under_review','endorsed','approved','rejected','published') DEFAULT 'draft'");

        Schema::table('articles', function (Blueprint $table) {
            if (Schema::hasColumn('articles', 'published_at')) {
                $table->dropColumn('published_at');
            }
            if (Schema::hasColumn('articles', 'scheduled_at')) {
                $table->dropColumn('scheduled_at');
            }
        });
    }
};
