<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Creates the tasks table. */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('article_id')->nullable()->constrained('articles')->nullOnDelete();
            $table->foreignId('assignee_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('section_id')->nullable()->constrained('sections')->nullOnDelete();
            $table->enum('type', ['writing', 'illustration', 'photography', 'layout', 'editing'])
                  ->default('writing');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['pending', 'in_progress', 'submitted', 'returned', 'completed'])
                  ->default('pending');
            $table->date('deadline')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->string('monitoring_sheet_url')->nullable();
            $table->unsignedInteger('word_count_target')->nullable();
            $table->timestamps();
        });
    }

    /** Drops the tasks table. */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
