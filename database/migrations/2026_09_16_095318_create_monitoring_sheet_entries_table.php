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
        Schema::create('monitoring_sheet_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monitoring_sheet_id')->constrained('monitoring_sheets')->cascadeOnDelete();
            
            // Basic Information
            $table->string('topic')->nullable();
            $table->string('section')->default('News');
            $table->string('article_type')->nullable();
            $table->string('medium')->default('English');
            $table->string('writer_assigned')->nullable();
            $table->string('media_type')->default('Photo/s');
            $table->string('artist_assigned')->nullable();
            
            // Data Gathering
            $table->boolean('interview_completed')->default(false);
            $table->string('storage_url')->nullable();
            $table->boolean('has_files')->default(false);
            
            // Editing Process
            $table->string('current_status')->default('Pending');
            
            // Additional fields for task tracking
            $table->text('description')->nullable();
            $table->enum('priority', ['Low', 'Moderate', 'High', 'Urgent'])->nullable();
            $table->date('deadline')->nullable();
            $table->time('deadline_time')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_sheet_entries');
    }
};
