<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** A note a reviewer attached to a highlighted passage of an article. */
class ReviewComment extends Model
{
    const STATUS_OPEN = 'open';
    const STATUS_RESOLVED = 'resolved';

    protected $fillable = [
        'article_id',
        'user_id',
        'author_role',
        'quote',
        'start_offset',
        'end_offset',
        'body',
        'status',
        'resolved_by',
        'resolved_at',
    ];

    /** Converts resolved_at to a date object. */
    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
        ];
    }

    /** The article the comment is about. */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    /** The reviewer who wrote the comment. */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** The person who marked the comment done. */
    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
