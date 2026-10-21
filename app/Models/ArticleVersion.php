<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** A saved copy of an article's text and artwork at one point in its review. */
class ArticleVersion extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'article_id',
        'user_id',
        'author_role',
        'version',
        'label',
        'title',
        'content',
        'cover_image',
        'media_files',
        'created_at',
    ];

    /** Converts media_files to an array and created_at to a date object. */
    protected function casts(): array
    {
        return [
            'media_files' => 'array',
            'created_at'  => 'datetime',
        ];
    }

    /**
     * Saves the article as it is now as the next version, unless it is identical to the latest one.
     * Returns the new version, or null when nothing had changed.
     */
    public static function record(Article $article, ?User $actor, string $label): ?self
    {
        $latest = static::where('article_id', $article->id)->orderByDesc('version')->first();

        if ($latest
            && $latest->title === $article->title
            && $latest->content === $article->content
            && $latest->cover_image === $article->cover_image
            && $latest->media_files === ($article->media_files ?? null)) {
            return null;
        }

        return static::create([
            'article_id'  => $article->id,
            'user_id'     => $actor?->id,
            'author_role' => $actor?->reviewRole(),
            'version'     => ($latest?->version ?? 0) + 1,
            'label'       => $label,
            'title'       => $article->title,
            'content'     => $article->content,
            'cover_image' => $article->cover_image,
            'media_files' => $article->media_files,
            'created_at'  => now(),
        ]);
    }

    /** The article the version belongs to. */
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    /** The person who saved the version. */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
