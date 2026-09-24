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
        'monitoring_sheet_url',
        'word_count_target',
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
