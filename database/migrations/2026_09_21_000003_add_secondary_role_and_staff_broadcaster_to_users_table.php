<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add staff_broadcaster to role ENUM
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','eic','section_editor','staff_writer','staff_artist','staff_broadcaster','reader') NOT NULL DEFAULT 'reader'");

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'secondary_role')) {
                $table->string('secondary_role')->nullable()->after('role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'secondary_role')) {
                $table->dropColumn('secondary_role');
            }
        });

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','eic','section_editor','staff_writer','staff_artist','reader') NOT NULL DEFAULT 'reader'");
    }
};
