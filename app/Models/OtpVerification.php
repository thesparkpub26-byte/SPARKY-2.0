<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpVerification extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'otp',
        'attempts',
        'expires_at',
    ];

    /** Converts the expires_at column to a date object. */
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    /** True when the code is past its expiry time. */
    public function isExpired(): bool
    {
        return now()->isAfter($this->expires_at);
    }
}
