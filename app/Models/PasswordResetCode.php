<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetCode extends Model
{
    protected $fillable = ['email', 'code_hash', 'attempts', 'expires_at', 'reset_token_hash', 'reset_expires_at'];

    protected function casts(): array
    {
        return [
            'expires_at'       => 'datetime',
            'reset_expires_at' => 'datetime',
        ];
    }

    /** One-way hash for codes and tokens (keyed with the app key). */
    public static function hash(string $value): string
    {
        return hash_hmac('sha256', $value, (string) config('app.key'));
    }
}
