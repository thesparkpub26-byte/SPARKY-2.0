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

    /** Converts the created_at column to a date object. */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    /** Writes one entry in the activity log: who did what, to which record. */
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

    /** Leaves out sign-in / sign-out entries, so activity feeds show real work. */
    public function scopeExcludingSessions($query)
    {
        return $query->whereNotIn('action', self::SESSION_ACTIONS);
    }

    /** The user who performed the action. */
    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
