<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('monitoring_sheet_entries', function (Blueprint $table) {
            $table->string('article_headline')->nullable()->after('description');
            $table->string('article_author')->nullable()->after('article_headline');
            $table->text('article_content')->nullable()->after('article_author');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monitoring_sheet_entries', function (Blueprint $table) {
            $table->dropColumn(['article_headline', 'article_author', 'article_content']);
        });
    }
};
