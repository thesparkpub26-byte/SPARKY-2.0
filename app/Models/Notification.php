<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'data',
        'read_at',
        'action_url',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'read_at' => 'datetime',
        ];
    }

    // Type constants
    const TYPE_TASK_ASSIGNED = 'task_assigned';
    const TYPE_TASK_SUBMITTED = 'task_submitted';
    const TYPE_TASK_RETURNED = 'task_returned';
    const TYPE_TASK_COMPLETED = 'task_completed';
    const TYPE_ARTICLE_SUBMITTED = 'article_submitted';
    const TYPE_ARTICLE_ENDORSED = 'article_endorsed';
    const TYPE_ARTICLE_APPROVED = 'article_approved';
    const TYPE_ARTICLE_REJECTED = 'article_rejected';
    const TYPE_GENERAL = 'general';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helpers
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    public function markAsRead(): void
    {
        if (is_null($this->read_at)) {
            $this->update(['read_at' => now()]);
        }
    }
}
