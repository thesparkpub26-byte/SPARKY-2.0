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

    protected static function booted(): void
    {
        // Skip an exact repeat of a notification the user has not read yet (or got moments ago),
        // e.g. a double-clicked Submit/Complete button or a credits list that is re-saved.
        static::creating(function (Notification $notification) {
            return !static::where('user_id', $notification->user_id)
                ->where('type', $notification->type)
                ->where('title', $notification->title)
                ->where('message', $notification->message)
                ->where(function ($q) {
                    $q->whereNull('read_at')->orWhere('created_at', '>=', now()->subMinutes(10));
                })
                ->get()
                ->contains(fn ($existing) => $existing->data == $notification->data);
        });
    }

    /**
     * Delete a user's notifications that point at a task or article that no longer exists,
     * so deleted work does not leave stale notifications behind.
     */
    public static function purgeOrphansFor(int $userId): void
    {
        $rows = static::where('user_id', $userId)->whereNotNull('data')->get(['id', 'data']);

        $taskIds = $rows->map(fn ($n) => $n->data['task_id'] ?? null)->filter()->unique()->all();
        $articleIds = $rows->map(fn ($n) => $n->data['article_id'] ?? null)->filter()->unique()->all();

        $liveTasks = $taskIds ? Task::whereIn('id', $taskIds)->pluck('id')->all() : [];
        $liveArticles = $articleIds ? Article::whereIn('id', $articleIds)->pluck('id')->all() : [];

        $orphans = $rows->filter(function ($n) use ($liveTasks, $liveArticles) {
            $taskId = $n->data['task_id'] ?? null;
            $articleId = $n->data['article_id'] ?? null;
            return ($taskId && !in_array($taskId, $liveTasks))
                || ($articleId && !in_array($articleId, $liveArticles));
        })->pluck('id');

        if ($orphans->isNotEmpty()) {
            static::whereIn('id', $orphans)->delete();
        }
    }

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
