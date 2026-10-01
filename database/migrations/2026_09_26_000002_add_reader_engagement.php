<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Adds read and share counters to articles and creates the comments table. */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->unsignedInteger('reads_count')->default(0);
            $table->unsignedInteger('shares_count')->default(0);
        });

        Schema::create('article_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('body');
            $table->timestamps();
        });
    }

    /** Drops the comments table and the counters. */
    public function down(): void
    {
        Schema::dropIfExists('article_comments');

        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['reads_count', 'shares_count']);
        });
    }
};
