<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Adds the column that records which reviewer returned a task. */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (!Schema::hasColumn('tasks', 'returned_by_role')) {
                $table->string('returned_by_role')->nullable()->after('status');
            }
        });
    }

    /** Removes the returned-by column. */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (Schema::hasColumn('tasks', 'returned_by_role')) {
                $table->dropColumn('returned_by_role');
            }
        });
    }
};
