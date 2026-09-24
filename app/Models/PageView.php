<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PageView extends Model
{
    protected $fillable = [
        'page_path',
        'page_title',
        'article_id',
        'visitor_hash',
    ];

    /** An anonymous, stable id for a visitor: the same person on the same device gets the same value. */
    public static function visitorHash(Request $request): string
    {
        return hash('sha256', implode('|', [$request->ip(), $request->userAgent(), config('app.key')]));
    }
}
