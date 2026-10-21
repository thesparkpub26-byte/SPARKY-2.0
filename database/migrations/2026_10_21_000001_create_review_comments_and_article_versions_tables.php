<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Creates the highlighted review comments and the saved versions of an article. */
    public function up(): void
    {
        // A reviewer highlights a passage of the article and says what to change; the writer marks it done
        Schema::create('review_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('author_role')->nullable();
            // The highlighted words, and where they were in the article text when the comment was written
            $table->text('quote');
            $table->unsignedInteger('start_offset')->nullable();
            $table->unsignedInteger('end_offset')->nullable();
            $table->text('body');
            $table->string('status')->default('open');
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            $table->index(['article_id', 'status']);
        });

        // A copy of the article's text each time the writer submits it or a reviewer edits it
        Schema::create('article_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('author_role')->nullable();
            $table->unsignedInteger('version');
            $table->string('label');
            $table->string('title', 500);
            $table->longText('content')->nullable();
            $table->text('cover_image')->nullable();
            $table->json('media_files')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['article_id', 'version']);
        });
    }

    /** Drops both tables. */
    public function down(): void
    {
        Schema::dropIfExists('article_versions');
        Schema::dropIfExists('review_comments');
    }
};
