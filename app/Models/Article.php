<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'excerpt',
        'author_id',
        'section_id',
        'status',
        'type',
        'word_count',
        'submitted_at',
        'endorsed_at',
        'approved_at',
        'rejected_at',
        'rejection_reason',
        'eic_notes',
        'editor_notes',
        'cover_image',
        'media_files',
        'monitoring_sheet_url',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'endorsed_at'  => 'datetime',
            'approved_at'  => 'datetime',
            'rejected_at'  => 'datetime',
            'media_files'  => 'array',
        ];
    }

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_UNDER_REVIEW = 'under_review';
    const STATUS_ENDORSED = 'endorsed';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_PUBLISHED = 'published';

    // Type constants
    const TYPE_ARTICLE = 'article';
    const TYPE_FEATURE = 'feature';
    const TYPE_OPINION = 'opinion';
    const TYPE_PHOTO_ESSAY = 'photo_essay';
    const TYPE_ILLUSTRATION = 'illustration';

    // Relationships
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // Status helpers
    public function isDraft(): bool { return $this->status === self::STATUS_DRAFT; }
    public function isSubmitted(): bool { return $this->status === self::STATUS_SUBMITTED; }
    public function isEndorsed(): bool { return $this->status === self::STATUS_ENDORSED; }
    public function isApproved(): bool { return $this->status === self::STATUS_APPROVED; }
    public function isPublished(): bool { return $this->status === self::STATUS_PUBLISHED; }
}
