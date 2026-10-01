<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Final credits on a video (Reporter, Scriptwriter, Videographer/s, Video Editor/s),
// set by the Head / Assistant Head Broadcaster before sending it to the EIC.
// Each role can hold several people; a credited user sees the video in their own list.
return new class extends Migration
{
    /** Creates the table that credits contributors on an article. */
    public function up(): void
    {
        Schema::create('article_credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('articles')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('role', 30);
            $table->timestamps();

            $table->unique(['article_id', 'user_id', 'role']);
        });
    }

    /** Drops the article credits table. */
    public function down(): void
    {
        Schema::dropIfExists('article_credits');
    }
};
