<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Forgot-password: an emailed 6-digit code, then a short-lived reset token once the code is verified.
        // Both are stored hashed, so a database leak doesn't hand out working codes.
        Schema::create('password_reset_codes', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('code_hash', 64)->nullable();
            $table->unsignedTinyInteger('attempts')->default(0);
            // nullable on purpose: MySQL gives the first NOT NULL timestamp column an implicit ON UPDATE CURRENT_TIMESTAMP
            $table->timestamp('expires_at')->nullable();
            $table->string('reset_token_hash', 64)->nullable();
            $table->timestamp('reset_expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('unsubscribe_token', 64)->unique();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_subscribers');
        Schema::dropIfExists('password_reset_codes');
    }
};
