<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Adds a published date to gallery photos and published issues. */
    public function up(): void
    {
        Schema::table('gallery_photos', function (Blueprint $table) {
            if (!Schema::hasColumn('gallery_photos', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('artist_id');
            }
        });

        Schema::table('published_issues', function (Blueprint $table) {
            if (!Schema::hasColumn('published_issues', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('uploaded_by');
            }
        });
    }

    /** Removes the published date columns. */
    public function down(): void
    {
        Schema::table('gallery_photos', function (Blueprint $table) {
            if (Schema::hasColumn('gallery_photos', 'published_at')) {
                $table->dropColumn('published_at');
            }
        });

        Schema::table('published_issues', function (Blueprint $table) {
            if (Schema::hasColumn('published_issues', 'published_at')) {
                $table->dropColumn('published_at');
            }
        });
    }
};
