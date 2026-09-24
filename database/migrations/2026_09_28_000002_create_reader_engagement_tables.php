<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // One like per reader per article
        Schema::create('article_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['article_id', 'user_id']);
        });

        // "Saved for later"
        Schema::create('article_bookmarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['article_id', 'user_id']);
            $table->index(['user_id', 'created_at']);
        });

        // A reader flagging a comment for the Editor-in-Chief; one report per reader per comment
        Schema::create('comment_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_comment_id')->constrained('article_comments')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('reason', 30);
            $table->string('details', 300)->nullable();
            $table->timestamps();

            $table->unique(['article_comment_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comment_reports');
        Schema::dropIfExists('article_bookmarks');
        Schema::dropIfExists('article_likes');
    }
};
