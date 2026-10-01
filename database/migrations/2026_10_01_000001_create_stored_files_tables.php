<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Uploaded photos and PDFs live in the database instead of on the server's disk (a host like Render wipes
// its disk on every deploy). One row per file in stored_files, and the bytes in stored_file_chunks in
// pieces of 256 KB, so a 35 MB PDF never has to fit in one query (MySQL's default packet limit is 1 MB).
return new class extends Migration
{
    /** Creates the tables that keep uploaded files (and their chunks) in the database. */
    public function up(): void
    {
        Schema::create('stored_files', function (Blueprint $table) {
            $table->id();
            $table->string('path')->unique();          // e.g. article-media/AbC123.jpg, the same value the site already stores
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('size')->default(0);
            $table->string('sha1', 40)->default('');
            $table->timestamps();
        });

        Schema::create('stored_file_chunks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stored_file_id')->constrained('stored_files')->cascadeOnDelete();
            $table->unsignedInteger('seq');
            $table->binary('data');
            $table->unique(['stored_file_id', 'seq']);
        });

        // Laravel's binary column is a 64 KB BLOB on MySQL; a chunk needs up to 256 KB
        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            DB::statement('ALTER TABLE stored_file_chunks MODIFY data MEDIUMBLOB NOT NULL');
        }
    }

    /** Drops the stored files tables. */
    public function down(): void
    {
        Schema::dropIfExists('stored_file_chunks');
        Schema::dropIfExists('stored_files');
    }
};
