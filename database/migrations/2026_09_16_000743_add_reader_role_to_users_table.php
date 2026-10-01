<?php

use Database\Support\EnumColumn;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /** Adds the reader role to the allowed user roles. */
    public function up(): void
    {
        // MySQL requires re-declaring the full ENUM to add a value
        EnumColumn::change('users', 'role', ['admin', 'eic', 'section_editor', 'staff_writer', 'staff_artist', 'reader'], 'reader');
    }

    /** Removes the reader role from the allowed user roles. */
    public function down(): void
    {
        EnumColumn::change('users', 'role', ['admin', 'eic', 'section_editor', 'staff_writer', 'staff_artist'], 'staff_writer');
    }
};
