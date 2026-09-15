<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL requires re-declaring the full ENUM to add a value
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','eic','section_editor','staff_writer','staff_artist','reader') NOT NULL DEFAULT 'reader'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','eic','section_editor','staff_writer','staff_artist') NOT NULL DEFAULT 'staff_writer'");
    }
};
