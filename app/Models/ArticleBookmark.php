<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleBookmark extends Model
{
    protected $fillable = ['article_id', 'user_id'];

    /** The article the reader saved. */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
