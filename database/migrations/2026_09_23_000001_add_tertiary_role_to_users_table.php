<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Adds the third role (title) column to users. */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'tertiary_role')) {
                $table->string('tertiary_role')->nullable()->after('secondary_role');
            }
        });
    }

    /** Removes the third role column. */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'tertiary_role')) {
                $table->dropColumn('tertiary_role');
            }
        });
    }
};
