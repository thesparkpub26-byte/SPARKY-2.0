<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Creates the sections table (News, Features, Sports...). */
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('color', 20)->default('#6B7280'); // Tailwind gray
            $table->timestamps();
        });
    }

    /** Drops the sections table. */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
