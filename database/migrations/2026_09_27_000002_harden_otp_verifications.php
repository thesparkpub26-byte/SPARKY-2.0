<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Adds a wrong-guess counter to pending sign-ups. */
    public function up(): void
    {
        Schema::table('otp_verifications', function (Blueprint $table) {
            // Wrong guesses so far; too many and the code is thrown away
            $table->unsignedTinyInteger('attempts')->default(0)->after('otp');
            // MySQL gave this first NOT NULL timestamp an implicit ON UPDATE CURRENT_TIMESTAMP, which would
            // quietly extend a code's life every time the row is touched. Nullable removes that behaviour.
            $table->timestamp('expires_at')->nullable()->change();
        });
    }

    /** Removes the wrong-guess counter. */
    public function down(): void
    {
        Schema::table('otp_verifications', function (Blueprint $table) {
            $table->dropColumn('attempts');
        });
    }
};
