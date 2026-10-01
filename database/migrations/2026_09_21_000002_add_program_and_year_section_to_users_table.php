<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Adds the program and year / section columns to users. */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('program')->nullable()->after('role');
            $table->string('year_section')->nullable()->after('program');
        });
    }

    /** Removes the program and year / section columns. */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['program', 'year_section']);
        });
    }
};
