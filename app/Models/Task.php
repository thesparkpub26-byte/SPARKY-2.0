<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'article_id',
        'assignee_id',
        'assigned_by',
        'section_id',
        'type',
        'priority',
        'status',
        'returned_by_role',
        'deadline',
        'completed_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_RETURNED = 'returned';
    const STATUS_COMPLETED = 'completed';

    // Priority constants
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    // Type constants
    const TYPE_WRITING = 'writing';
    const TYPE_ILLUSTRATION = 'illustration';
    const TYPE_PHOTOGRAPHY = 'photography';
    const TYPE_LAYOUT = 'layout';
    const TYPE_EDITING = 'editing';
    const TYPE_VIDEOGRAPHY = 'videography';
    const TYPE_VIDEO_EDITING = 'video_editing';

    // The assignee's title is remembered as of when the task was assigned (or reassigned)
    protected static function booted(): void
    {
        static::creating(function (Task $task) {
            if (blank($task->assignee_role) && $task->assignee_id) {
                $task->assignee_role = User::find($task->assignee_id)?->displayRole();
            }
        });

        static::updating(function (Task $task) {
            if ($task->isDirty('assignee_id') && !$task->isDirty('assignee_role')) {
                $task->assignee_role = User::find($task->assignee_id)?->displayRole();
            }
        });

        // The assign form names the section in the task's notes; keep it as a real section too, so that
        // every screen (and the published article) knows the section without reading the notes text.
        static::saving(function (Task $task) {
            if (blank($task->section_id)) {
                $task->section_id = Section::idFromNotes($task->notes);
            }
        });

        // An article takes the section of the task it was written for, unless it already has one
        static::saved(function (Task $task) {
            if ($task->article_id && $task->section_id) {
                Article::whereKey($task->article_id)->whereNull('section_id')->get()
                    ->each(fn (Article $article) => $article->update(['section_id' => $task->section_id]));
            }
        });
    }

    // Relationships
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
