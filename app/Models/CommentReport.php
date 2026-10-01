<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentReport extends Model
{
    public const REASONS = ['spam', 'abusive', 'misleading', 'other'];

    protected $fillable = ['article_comment_id', 'user_id', 'reason', 'details'];

    /** The comment that was reported. */
    public function comment()
    {
        return $this->belongsTo(ArticleComment::class, 'article_comment_id');
    }
}
