<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Creates the published issues (PDF) table. */
    public function up(): void
    {
        Schema::create('published_issues', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('pdf_path');
            // Populated later once the PDF is converted into a flipbook (reader-side).
            $table->string('flipbook_url')->nullable();
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /** Drops the published issues table. */
    public function down(): void
    {
        Schema::dropIfExists('published_issues');
    }
};
