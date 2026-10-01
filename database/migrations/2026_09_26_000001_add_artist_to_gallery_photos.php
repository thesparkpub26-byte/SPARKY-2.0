<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Adds the credited artist to gallery photos. */
    public function up(): void
    {
        Schema::table('gallery_photos', function (Blueprint $table) {
            // The artist (or Art Editor) credited for the photo; photos uploaded before this existed have none
            $table->foreignId('artist_id')->nullable()->after('uploaded_by')->constrained('users')->nullOnDelete();
        });
    }

    /** Removes the credited artist column. */
    public function down(): void
    {
        Schema::table('gallery_photos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('artist_id');
        });
    }
};
