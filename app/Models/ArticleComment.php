<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleComment extends Model
{
    protected $fillable = ['article_id', 'user_id', 'body'];

    /** The article the comment was written on. */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    /** The person who wrote the comment. */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
