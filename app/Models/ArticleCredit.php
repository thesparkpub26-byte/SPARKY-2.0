<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleCredit extends Model
{
    protected $fillable = ['article_id', 'user_id', 'role'];

    const ROLE_REPORTER = 'reporter';
    const ROLE_SCRIPTWRITER = 'scriptwriter';
    const ROLE_VIDEOGRAPHER = 'videographer';
    const ROLE_VIDEO_EDITOR = 'video_editor';

    const ROLES = [
        self::ROLE_REPORTER => 'Reporter',
        self::ROLE_SCRIPTWRITER => 'Scriptwriter',
        self::ROLE_VIDEOGRAPHER => 'Videographer',
        self::ROLE_VIDEO_EDITOR => 'Video Editor',
    ];

    // The crew member's title is remembered as of when they were credited
    protected static function booted(): void
    {
        static::creating(function (ArticleCredit $credit) {
            if (blank($credit->user_role) && $credit->user_id) {
                $credit->user_role = User::find($credit->user_id)?->displayRole();
            }
        });
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
