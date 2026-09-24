<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    protected $fillable = ['email', 'unsubscribe_token', 'unsubscribed_at'];

    protected function casts(): array
    {
        return [
            'unsubscribed_at' => 'datetime',
        ];
    }

    public function scopeActive($query)
    {
        return $query->whereNull('unsubscribed_at');
    }

    /** The link in every newsletter email that opens the unsubscribe page. */
    public function unsubscribeUrl(): string
    {
        return url('/unsubscribe/' . $this->unsubscribe_token);
    }
}
