<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Creates the articles table. */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('content')->nullable();
            $table->text('excerpt')->nullable();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('section_id')->nullable()->constrained('sections')->nullOnDelete();
            $table->enum('status', [
                'draft', 'submitted', 'under_review', 'endorsed', 'approved', 'rejected', 'published'
            ])->default('draft');
            $table->enum('type', [
                'article', 'feature', 'opinion', 'photo_essay', 'illustration'
            ])->default('article');
            $table->unsignedInteger('word_count')->default(0);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('endorsed_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('eic_notes')->nullable();
            $table->text('editor_notes')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('monitoring_sheet_url')->nullable();
            $table->timestamps();
        });
    }

    /** Drops the articles table. */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
