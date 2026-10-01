<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Adds the column that lists an article's uploaded media files. */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // JSON array of uploaded media file paths (stored in /storage/app/public/article-media)
            $table->json('media_files')->nullable()->after('cover_image');
        });
    }

    /** Removes the media files column. */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('media_files');
        });
    }
};
