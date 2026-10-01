<?php

use Database\Support\EnumColumn;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /** Adds the staff broadcaster role and the secondary role (title) column to users. */
    public function up(): void
    {
        // Add staff_broadcaster to role ENUM
        EnumColumn::change('users', 'role', ['admin', 'eic', 'section_editor', 'staff_writer', 'staff_artist', 'staff_broadcaster', 'reader'], 'reader');

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'secondary_role')) {
                $table->string('secondary_role')->nullable()->after('role');
            }
        });
    }

    /** Removes the secondary role column. */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'secondary_role')) {
                $table->dropColumn('secondary_role');
            }
        });

        EnumColumn::change('users', 'role', ['admin', 'eic', 'section_editor', 'staff_writer', 'staff_artist', 'reader'], 'reader');
    }
};
