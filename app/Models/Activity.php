<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    public $timestamps = false;

    /** Session noise: not recorded any more, and hidden from the activity lists if an old row exists. */
    public const SESSION_ACTIONS = ['Logged in', 'Logged out'];

    protected $fillable = [
        'actor_id',
        'action',
        'subject_type',
        'subject_id',
        'subject_label',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public static function record(?User $actor, string $action, ?Model $subject = null, ?string $subjectLabel = null): self
    {
        return static::create([
            'actor_id' => $actor?->id,
            'action' => $action,
            'subject_type' => $subject ? $subject::class : null,
            'subject_id' => $subject?->getKey(),
            'subject_label' => $subjectLabel ?? ($subject?->name ?? $subject?->title),
            'created_at' => now(),
        ]);
    }

    public function scopeExcludingSessions($query)
    {
        return $query->whereNotIn('action', self::SESSION_ACTIONS);
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
