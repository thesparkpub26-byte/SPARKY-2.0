<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Article;
use App\Models\ArticleBookmark;
use App\Models\ArticleComment;
use App\Models\ArticleLike;
use App\Models\CommentReport;
use App\Models\Notification;
use App\Models\PageView;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/** Public, reader-facing endpoints for a single published article and its engagement numbers. */
class ReaderArticleController extends Controller
{
    /** Only published, non-video articles are readable on the reader site. */
    private function readable(Article $article): Article
    {
        abort_unless($article->status === Article::STATUS_PUBLISHED && $article->type !== Article::TYPE_VIDEO, 404);

        return $article;
    }

    /** $role is the title held at the time; without one, the person's current title is used. */
    private function person(?User $user, ?string $role = null): ?array
    {
        if (!$user) return null;

        return [
            'id'     => $user->id,
            'name'   => $user->name,
            'role'   => $role ?: ($user->secondary_role ?: ucwords(str_replace('_', ' ', (string) $user->role))),
            'avatar' => $user->profile_picture ? '/storage/' . $user->profile_picture : null,
        ];
    }

    public function show(Request $request, Article $article)
    {
        $this->readable($article)->loadCount(['comments', 'likes']);
        $article->load(['author', 'section']);

        // The photojournalist / artist assigned to the article
        $contributors = Task::with('assignee')
            ->where('article_id', $article->id)
            ->whereIn('type', [Task::TYPE_ILLUSTRATION, Task::TYPE_PHOTOGRAPHY])
            ->whereNotNull('assignee_id')
            ->get()
            ->filter(fn (Task $t) => $t->assignee)
            ->unique('assignee_id')
            // Show the title they held when they were assigned to this article
            ->map(fn (Task $t) => $this->person($t->assignee, $t->assignee_role))
            ->values();

        // Same section as this article, which is already loaded: no need to fetch it again for each card
        $related = Article::where('status', Article::STATUS_PUBLISHED)
            ->where('type', '!=', Article::TYPE_VIDEO)
            ->where('id', '!=', $article->id)
            ->where('section_id', $article->section_id)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(3)
            ->get()
            ->map(fn (Article $a) => app(ArticleController::class)->toCard($a->setRelation('section', $article->section)));

        return response()->json([
            'id'             => $article->id,
            'title'          => $article->title,
            'category'       => $article->section?->name,
            'published_at'   => ($article->published_at ?? $article->created_at)?->toIso8601String(),
            'content'        => $article->content,
            // The 1-3 media uploads (not the thumbnail); the thumbnail only stands in when there are none
            'media'          => array_values(array_filter($article->media_files ?: [$article->cover_image])),
            // The author's title when the article was written, not what it is today
            'author'         => $this->person($article->author, $article->author_role),
            'contributors'   => $contributors,
            'reads'          => $article->reads_count,
            'shares'         => $article->shares_count,
            'comments_count' => $article->comments_count,
            'related'        => $related,
        ] + $this->engagement($article, $request->user('sanctum')));
    }

    /** Likes on the article, and (for a signed-in reader) whether they liked / saved it. */
    private function engagement(Article $article, ?User $user): array
    {
        return [
            // the page already counted them; the like / unlike answers count again
            'likes_count' => $article->likes_count ?? ArticleLike::where('article_id', $article->id)->count(),
            'liked'       => $user ? ArticleLike::where('article_id', $article->id)->where('user_id', $user->id)->exists() : false,
            'bookmarked'  => $user ? ArticleBookmark::where('article_id', $article->id)->where('user_id', $user->id)->exists() : false,
        ];
    }

    public function like(Request $request, Article $article)
    {
        $this->readable($article);
        ArticleLike::firstOrCreate(['article_id' => $article->id, 'user_id' => $request->user()->id]);

        return response()->json($this->engagement($article, $request->user()));
    }

    public function unlike(Request $request, Article $article)
    {
        $this->readable($article);
        ArticleLike::where('article_id', $article->id)->where('user_id', $request->user()->id)->delete();

        return response()->json($this->engagement($article, $request->user()));
    }

    public function bookmark(Request $request, Article $article)
    {
        $this->readable($article);
        ArticleBookmark::firstOrCreate(['article_id' => $article->id, 'user_id' => $request->user()->id]);

        return response()->json($this->engagement($article, $request->user()));
    }

    public function unbookmark(Request $request, Article $article)
    {
        $this->readable($article);
        ArticleBookmark::where('article_id', $article->id)->where('user_id', $request->user()->id)->delete();

        return response()->json($this->engagement($article, $request->user()));
    }

    /** The signed-in reader's saved articles, most recently saved first, nine at a time. */
    public function bookmarks(Request $request)
    {
        $page = ArticleBookmark::where('user_id', $request->user()->id)
            ->whereHas('article', fn ($q) => $q->where('status', Article::STATUS_PUBLISHED)->where('type', '!=', Article::TYPE_VIDEO))
            ->with('article.section:id,name')
            ->latest()->latest('id')
            ->paginate(9);

        $cards = app(ArticleController::class);

        return response()->json([
            'data'         => $page->getCollection()->map(fn (ArticleBookmark $b) => $cards->toCard($b->article))->values(),
            'current_page' => $page->currentPage(),
            'last_page'    => $page->lastPage(),
            'total'        => $page->total(),
        ]);
    }

    /** A signed-in reader flags a comment; every Editor-in-Chief is told so it can be reviewed and removed. */
    public function reportComment(Request $request, ArticleComment $comment)
    {
        $user = $request->user();
        if ($comment->user_id === $user->id) {
            return response()->json(['message' => "You can't report your own comment."], 422);
        }

        $validated = $request->validate([
            'reason'  => 'required|in:' . implode(',', CommentReport::REASONS),
            'details' => 'nullable|string|max:300',
        ]);

        $report = CommentReport::firstOrCreate(
            ['article_comment_id' => $comment->id, 'user_id' => $user->id],
            ['reason' => $validated['reason'], 'details' => $validated['details'] ?? null],
        );

        if ($report->wasRecentlyCreated) {
            $article = $comment->article;
            foreach (User::where('role', User::ROLE_EIC)->where('is_active', true)->get() as $eic) {
                Notification::create([
                    'user_id' => $eic->id,
                    'title'   => 'Comment reported',
                    'message' => "A reader comment on '{$article->title}' was reported as {$validated['reason']}.",
                    'type'    => Notification::TYPE_GENERAL,
                    'data'    => ['article_id' => $article->id, 'comment_id' => $comment->id],
                ]);
            }
        }

        return response()->json(['message' => 'Thank you. The editors will take a look.']);
    }

    /** Counts one open of the article page. */
    public function read(Article $article)
    {
        $this->readable($article);

        return response()->json(['reads' => $this->bump($article, 'reads_count')]);
    }

    /**
     * Counts one share (the page copies the link to the clipboard first). Copying the link again, or
     * pressing the button repeatedly, counts once per visitor per article per day.
     */
    public function share(Request $request, Article $article)
    {
        $this->readable($article);

        $first = Cache::store(config('cache.limiter'))->add('share:' . $article->id . ':' . PageView::visitorHash($request), 1, now()->addDay());

        return response()->json(['shares' => $first ? $this->bump($article, 'shares_count') : (int) $article->shares_count]);
    }

    /**
     * Adds one to a counter with a single UPDATE. It leaves updated_at alone: the sitemap reports that date to
     * search engines as "last modified", and a reader opening an article is not an edit.
     */
    private function bump(Article $article, string $column): int
    {
        DB::table('articles')->where('id', $article->id)->increment($column);

        return (int) $article->{$column} + 1;
    }

    /** Comments, newest first, three at a time: ?offset= is how many the page has already loaded. */
    public function comments(Request $request, Article $article)
    {
        $this->readable($article);
        $perPage = 3;

        // One extra row tells us whether there is another page
        $rows = $article->comments()->with('user')->latest()->latest('id')
            ->offset(max(0, $request->integer('offset')))
            ->limit($perPage + 1)
            ->get();

        return response()->json([
            'data'     => $rows->take($perPage)->map(fn (ArticleComment $c) => $this->comment($c))->values(),
            'has_more' => $rows->count() > $perPage,
        ]);
    }

    /** Signed-in readers only (the route sits behind auth). */
    public function storeComment(Request $request, Article $article)
    {
        $this->readable($article);
        $validated = $request->validate(['body' => 'required|string|max:1000']);

        $comment = $article->comments()->create([
            'user_id' => $request->user()->id,
            'body'    => trim($validated['body']),
        ])->load('user');

        return response()->json([
            'comment'        => $this->comment($comment),
            'comments_count' => $article->comments()->count(),
        ], 201);
    }

    /** Only the person who wrote a comment can edit it. */
    public function updateComment(Request $request, ArticleComment $comment)
    {
        if ($comment->user_id !== $request->user()->id) {
            return response()->json(['message' => 'You can only edit your own comments.'], 403);
        }

        $validated = $request->validate(['body' => 'required|string|max:1000']);
        $comment->update(['body' => trim($validated['body'])]);

        return response()->json(['comment' => $this->comment($comment->load('user'))]);
    }

    /** A comment can be deleted by its author, or by the Editor-in-Chief (or an admin) as moderation. */
    public function destroyComment(Request $request, ArticleComment $comment)
    {
        $user = $request->user();
        $isOwner = $comment->user_id === $user->id;
        if (!$isOwner && !in_array($user->role, ['eic', 'admin'])) {
            return response()->json(['message' => 'You can only delete your own comments.'], 403);
        }

        $article = $comment->article;
        if (!$isOwner) {
            Activity::record($user, 'Deleted a reader comment', $article);
        }
        $comment->delete();

        return response()->json(['comments_count' => $article->comments()->count()]);
    }

    private function comment(ArticleComment $c): array
    {
        return [
            'id'         => $c->id,
            'body'       => $c->body,
            'created_at' => $c->created_at?->toIso8601String(),
            'edited'     => $c->updated_at && $c->created_at && $c->updated_at->gt($c->created_at),
            'user'       => $this->person($c->user, $c->user?->role === 'reader' ? 'Reader' : null),
        ];
    }
}
