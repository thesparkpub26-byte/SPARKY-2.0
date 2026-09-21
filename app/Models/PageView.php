<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    protected $fillable = [
        'page_path',
        'page_title',
        'article_id',
        'visitor_hash',
    ];
}
