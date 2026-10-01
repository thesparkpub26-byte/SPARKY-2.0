<?php

namespace App\Models;

use App\Support\PublicCache;
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
        'editor_notes',
        'cover_image',
        'media_files',
        'video_url',
        'video_category',
        'scheduled_at',
        'published_at',
    ];

    /**
     * Converts the workflow timestamps (submitted, endorsed, approved, rejected, scheduled, published) to date
     * objects and media_files to an array.
     */
    protected function casts(): array
    {
        return [
            'submitted_at'  => 'datetime',
            'endorsed_at'   => 'datetime',
            'approved_at'   => 'datetime',
            'rejected_at'   => 'datetime',
            'scheduled_at'  => 'datetime',
            'published_at'  => 'datetime',
            'media_files'   => 'array',
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
    const STATUS_SCHEDULED = 'scheduled';

    // Type constants
    const TYPE_ARTICLE = 'article';
    const TYPE_FEATURE = 'feature';
    const TYPE_OPINION = 'opinion';
    const TYPE_PHOTO_ESSAY = 'photo_essay';
    const TYPE_ILLUSTRATION = 'illustration';
    const TYPE_VIDEO = 'video';

    // Video categories (EIC picks one when reviewing / editing a video)
    const VIDEO_CATEGORIES = ['Documentary', 'Reel', 'Telesiklab'];

    // The author's title is remembered as of when the article was written (or when its author changed)
    protected static function booted(): void
    {
        // Readers' cached lists (home, categories, videos) are rebuilt whenever an article changes
        static::saved(fn () => PublicCache::forget('articles'));
        static::deleted(fn () => PublicCache::forget('articles'));

        static::creating(function (Article $article) {
            if (blank($article->author_role) && $article->author_id) {
                $article->author_role = User::find($article->author_id)?->displayRole();
            }
        });

        static::updating(function (Article $article) {
            if ($article->isDirty('author_id') && !$article->isDirty('author_role')) {
                $article->author_role = User::find($article->author_id)?->displayRole();
            }
        });
    }

    /** Publishes every scheduled article whose time has come. Returns how many went live. */
    public static function publishDue(): int
    {
        return static::where('status', self::STATUS_SCHEDULED)
            ->where('scheduled_at', '<=', now())
            ->get()
            ->each(fn (Article $article) => $article->update([
                'status'       => self::STATUS_PUBLISHED,
                'published_at' => $article->scheduled_at,
            ]))
            ->count();
    }

    // Relationships
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /** The section the article belongs to. */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    /** The tasks (writing, artwork, video crew) linked to the article. */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /** The likes readers gave the article. */
    public function likes()
    {
        return $this->hasMany(ArticleLike::class);
    }

    /** The comments readers wrote on the article. */
    public function comments()
    {
        return $this->hasMany(ArticleComment::class);
    }

    /** The contributors credited on the article. */
    public function credits()
    {
        return $this->hasMany(ArticleCredit::class);
    }

    /** The 11-character video id in any common YouTube link (watch?v=, youtu.be/, /embed/, /shorts/, /live/), or null. */
    public static function youtubeId(?string $url): ?string
    {
        return preg_match('~(?:youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|live/|v/)|youtu\.be/)([A-Za-z0-9_-]{11})~i', (string) $url, $m) ? $m[1] : null;
    }

    // Status helpers
    public function isDraft(): bool { return $this->status === self::STATUS_DRAFT; }
    /** True when the article has been submitted for review. */
    public function isSubmitted(): bool { return $this->status === self::STATUS_SUBMITTED; }
    /** True when a section editor / copyreader has endorsed the article to the EIC. */
    public function isEndorsed(): bool { return $this->status === self::STATUS_ENDORSED; }
    /** True when the EIC has approved the article. */
    public function isApproved(): bool { return $this->status === self::STATUS_APPROVED; }
    /** True when the article is live on the reader site. */
    public function isPublished(): bool { return $this->status === self::STATUS_PUBLISHED; }
}
