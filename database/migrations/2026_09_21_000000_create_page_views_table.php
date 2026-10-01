<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Creates the table that records page visits for analytics. */
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('page_path', 500);
            $table->string('page_title')->nullable();
            $table->foreignId('article_id')->nullable()->constrained()->nullOnDelete();
            $table->string('visitor_hash', 64)->nullable()->index();
            $table->timestamps();

            $table->index(['created_at', 'page_path']);
        });
    }

    /** Drops the page visits table. */
    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
