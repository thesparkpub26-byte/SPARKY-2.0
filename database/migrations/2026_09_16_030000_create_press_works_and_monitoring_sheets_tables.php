<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Creates the academic years (press works) and monitoring sheets tables. */
    public function up(): void
    {
        Schema::create('press_works', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('academic_year')->default('2025-2026');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('monitoring_sheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('press_work_id')->constrained('press_works')->cascadeOnDelete();
            $table->enum('publication_type', ['Newsletter', 'Tabloid', 'Magazine', 'Litfolio']);
            $table->string('title');
            $table->string('status')->default('Active');
            $table->timestamps();
            $table->unique(['press_work_id', 'publication_type']);
        });
    }

    /** Drops the monitoring sheets and academic years tables. */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_sheets');
        Schema::dropIfExists('press_works');
    }
};
