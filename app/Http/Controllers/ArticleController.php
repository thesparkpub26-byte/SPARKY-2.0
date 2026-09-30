<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleCredit;
use App\Models\Activity;
use App\Models\Notification;
use App\Models\Task;
use App\Models\User;
use App\Support\Html;
use App\Support\Images;
use App\Support\PopularArticles;
use App\Support\PublicCache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /** List articles with optional filters */
    public function index(Request $request)
    {
        $this->publishDueSchedules();

        $query = Article::with(['author', 'section', 'tasks.assignee', 'tasks.assignedBy', 'credits.user']);

        $this->filterBy($query, $request, ['status' => 'status', 'type' => 'type'], ['author_id' => 'author_id', 'section_id' => 'section_id']);
        // Videos a user was credited on (reporter, scriptwriter, videographer, video editor)
        if ($request->has('credited_to')) {
            $credited = $request->input('credited_to');
            is_string($credited) && ctype_digit($credited)
                ? $query->whereHas('credits', fn ($q) => $q->where('user_id', (int) $credited))
                : $query->whereRaw('0 = 1');
        }
        // Videos live in the same table but are a separate content type: they only show up
        // when asked for (?type=video) or explicitly included (?include_videos=1).
        if (!$request->has('type') && !$request->boolean('include_videos')) {
            $query->where('type', '!=', Article::TYPE_VIDEO);
        }

        return response()->json($query->orderByDesc('created_at')->get());
    }

    /**
     * Public: the 4 most recently published articles for the reader home carousel.
     * Videos are excluded (gallery photos and issues live in their own tables).
     */
    public function carousel()
    {
        $slides = PublicCache::remember(request(), 'articles', [], fn () => $this->publishedArticles()->limit(4)->get()
            ->map(fn (Article $a) => [
                'id'           => $a->id,
                'title'        => $a->title,
                'author'       => $a->author?->name,
                'published_at' => ($a->published_at ?? $a->created_at)?->toIso8601String(),
                'image'        => $a->cover_image ?: ($a->media_files[0] ?? null),
            ])->all());

        return response()->json($slides);
    }

    /**
     * Public: the reader "Popular now" lists (default 6, max 12): the most-read articles of the week.
     * See PopularArticles for how the week and the ranking work.
     */
    public function popular(Request $request)
    {
        $limit = max(1, min(12, $request->integer('limit', 6)));

        return response()->json(PublicCache::remember($request, 'articles', ['limit'], function () use ($limit) {
            $this->publishDueSchedules();

            $ids = PopularArticles::ids($limit);
            $articles = Article::with(['author:id,name', 'section:id,name'])->whereIn('id', $ids)->get()->keyBy('id');

            // Keep the ranking's order
            return collect($ids)
                ->map(fn ($id) => $articles->get($id))
                ->filter()
                ->map(fn (Article $a) => $this->toCard($a))
                ->values()
                ->all();
        }));
    }

    /**
     * Public: published articles, 5 per page, newest first. Pass ?category=News (etc.) to
     * only show that section; without one it lists the latest across every category.
     */
    public function categoryArticles(Request $request)
    {
        return response()->json(PublicCache::remember($request, 'articles', ['category', 'page'], fn () => $this->categoryPage($request)));
    }

    private function categoryPage(Request $request): array
    {
        $category = strtolower(trim((string) $request->query('category', '')));

        $query = $this->publishedArticles();
        if ($category !== '') {
            // The "Feature" category also covers the older plural "Features" section
            $names = $category === 'feature' ? ['feature', 'features'] : [$category];
            $query->whereHas('section', fn ($q) => $q->whereRaw(
                'LOWER(name) IN (' . implode(',', array_fill(0, count($names), '?')) . ')',
                $names
            ));
        }

        $page = $query->paginate(5);

        return [
            'data'         => $page->getCollection()->map(fn (Article $a) => $this->toCard($a))->values()->all(),
            'current_page' => $page->currentPage(),
            'last_page'    => $page->lastPage(),
            'total'        => $page->total(),
        ];
    }

    /**
     * Public: published videos (the broadcasting team's work), newest first. Optional ?limit=;
     * with ?page= the list is paginated (9 a page) and wrapped with the paging info.
     */
    public function videos(Request $request)
    {
        return response()->json(PublicCache::remember($request, 'articles', ['limit', 'page'], fn () => $this->videoList($request)));
    }

    private function videoList(Request $request): array
    {
        $this->publishDueSchedules();

        $query = Article::where('status', Article::STATUS_PUBLISHED)
            ->where('type', Article::TYPE_VIDEO)
            ->orderByDesc('published_at')
            ->orderByDesc('id');
        $toVideo = fn (Article $a) => $this->toVideoCard($a);

        if ($request->filled('page')) {
            $page = $query->paginate(9);

            return [
                'data'         => $page->getCollection()->map($toVideo)->values()->all(),
                'current_page' => $page->currentPage(),
                'last_page'    => $page->lastPage(),
                'total'        => $page->total(),
            ];
        }

        if ($request->filled('limit')) {
            $query->limit(max(1, min(50, $request->integer('limit'))));
        }

        return $query->get()->map($toVideo)->all();
    }

    /** A video's card: its category as the badge, a YouTube thumbnail when there's no cover, and the link to watch. */
    public function toVideoCard(Article $a): array
    {
        $youtubeId = Article::youtubeId($a->video_url);

        return array_merge($this->toCard($a), [
            'badge'     => $a->video_category ?: 'Video',
            'image'     => $a->cover_image ?: ($youtubeId ? "https://img.youtube.com/vi/{$youtubeId}/hqdefault.jpg" : null),
            'readTime'  => 'Watch video',
            'video_url' => $a->video_url,
        ]);
    }

    /** Card shape shared by the reader's article grids and lists. */
    public function toCard(Article $a): array
    {
        $plain = trim(preg_replace('/\s+/', ' ', strip_tags(str_replace(['</p>', '<br>', '<br/>', '</div>'], ' ', $a->content ?? ''))));
        $words = $a->word_count ?: ($plain === '' ? 0 : count(preg_split('/\s+/', $plain)));

        return [
            'id'       => $a->id,
            'badge'    => $a->section?->name,
            'title'    => $a->title,
            'excerpt'  => Str::limit($a->excerpt ?: $plain, 110),
            'image'    => $a->cover_image ?: ($a->media_files[0] ?? null),
            'date'     => ($a->published_at ?? $a->created_at)?->format('M j, Y'),
            'readTime' => max(1, (int) ceil($words / 200)) . ' min' . ($words > 200 ? 's' : '') . ' read',
        ];
    }

    /**
     * Who may edit or submit an article: reviewers (editors, copyreaders), its author, and anyone
     * assigned a task on it or credited on it.
     */
    private function canWorkOn(User $user, Article $article): bool
    {
        return $user->isReviewer()
            || $article->author_id === $user->id
            || $article->tasks()->where('assignee_id', $user->id)->exists()
            || $article->credits()->where('user_id', $user->id)->exists();
    }

    /** Newest published, non-video articles first. */
    private function publishedArticles()
    {
        $this->publishDueSchedules();

        return Article::with(['author:id,name', 'section:id,name'])
            ->where('status', Article::STATUS_PUBLISHED)
            ->where('type', '!=', Article::TYPE_VIDEO)
            ->orderByDesc('published_at')
            ->orderByDesc('id');
    }

    /** Get a single article */
    public function show(Article $article)
    {
        $this->publishDueSchedules();
        return response()->json($article->fresh()->load(['author', 'section', 'tasks.assignee', 'tasks.assignedBy', 'credits.user']));
    }

    /** Create a new article */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'                 => 'required|string|max:500',
            'content'               => 'nullable|string',
            'excerpt'               => 'nullable|string|max:1000',
            'section_id'            => 'nullable|exists:sections,id',
            'type'                  => 'nullable|in:article,feature,opinion,photo_essay,illustration,video',
            'word_count'            => 'nullable|integer|min:0',
            'cover_image'           => ['nullable', 'string', 'not_regex:/^\\s*(javascript|vbscript|data:text)/i'],
            'media_files'           => 'nullable|array',
            'media_files.*'         => 'nullable|string',
            'video_url'             => 'nullable|url:http,https|max:500',
            'video_category'        => 'nullable|in:' . implode(',', Article::VIDEO_CATEGORIES),
            'editor_notes'          => 'nullable|string',
        ]);

        if (array_key_exists('content', $validated)) {
            $validated['content'] = Html::clean($validated['content']);
        }

        $validated['author_id'] = $request->user()->id;
        $article = Article::create($validated);
        Activity::record($request->user(), 'Created an article', $article);

        return response()->json($article->load(['author', 'section']), 201);
    }

    /** Update article content/metadata */
    public function update(Request $request, Article $article)
    {
        $user = $request->user();
        if (!$this->canWorkOn($user, $article)) {
            return response()->json(['message' => 'You are not allowed to edit this article.'], 403);
        }

        $validated = $request->validate([
            'title'                 => 'sometimes|string|max:500',
            'content'               => 'nullable|string',
            'excerpt'               => 'nullable|string|max:1000',
            'status'                => 'nullable|in:draft,submitted,under_review,endorsed,approved,rejected,published,scheduled',
            'section_id'            => 'nullable|exists:sections,id',
            'type'                  => 'nullable|in:article,feature,opinion,photo_essay,illustration,video',
            'word_count'            => 'nullable|integer|min:0',
            'cover_image'           => ['nullable', 'string', 'not_regex:/^\\s*(javascript|vbscript|data:text)/i'],
            'media_files'           => 'nullable|array',
            'media_files.*'         => 'nullable|string',
            'video_url'             => 'nullable|url:http,https|max:500',
            'video_category'        => 'nullable|in:' . implode(',', Article::VIDEO_CATEGORIES),
            'editor_notes'          => 'nullable|string',
            'scheduled_at'          => 'required_if:status,scheduled|nullable|date',
        ]);

        if (array_key_exists('content', $validated)) {
            $validated['content'] = Html::clean($validated['content']);
        }

        // Going live (or being approved for it) is the Editor-in-Chief's call
        $newStatus = $validated['status'] ?? null;
        if ($newStatus && $newStatus !== $article->status
            && in_array($newStatus, [Article::STATUS_PUBLISHED, Article::STATUS_SCHEDULED, Article::STATUS_APPROVED], true)
            && !in_array($user->role, ['eic', 'admin'], true)) {
            return response()->json(['message' => 'Only the Editor-in-Chief can publish or schedule an article.'], 403);
        }

        if (($validated['status'] ?? null) === Article::STATUS_SCHEDULED) {
            $validated['published_at'] = null;
        } elseif (($validated['status'] ?? null) === Article::STATUS_PUBLISHED) {
            $validated['published_at'] = $article->published_at ?? now();
            $validated['scheduled_at'] = null;
        }

        $article->update($validated);
        Activity::record($request->user(), 'Updated an article', $article);

        return response()->json($article->load(['author', 'section']));
    }

    /** Replace a video's credits and notify anyone newly credited. */
    private function syncCredits(Article $article, array $credits): void
    {
        $existing = $article->credits()->get()->map(fn ($c) => $c->user_id . ':' . $c->role)->all();

        $article->credits()->delete();
        $added = [];
        foreach (array_keys(ArticleCredit::ROLES) as $role) {
            foreach (array_unique($credits[$role] ?? []) as $userId) {
                $article->credits()->create(['user_id' => $userId, 'role' => $role]);
                if (!in_array($userId . ':' . $role, $existing, true)) {
                    $added[] = [$userId, $role];
                }
            }
        }

        foreach ($added as [$userId, $role]) {
            Notification::create([
                'user_id' => $userId,
                'title'   => 'Credited on a Video',
                'message' => "You were credited as " . ArticleCredit::ROLES[$role] . " on '{$article->title}'.",
                'type'    => Notification::TYPE_GENERAL,
                'data'    => ['article_id' => $article->id],
            ]);
        }
    }

    /**
     * Publish a video straight away ("Publish Automatic/Past Video"), skipping the
     * presenter → Head Broadcaster → EIC review. Everyone credited sees it under their own work.
     */
    public function publishDirectVideo(Request $request)
    {
        $user = $request->user();
        $isBroadcastHead = $user->role === 'section_editor'
            && stripos(($user->secondary_role ?? '') . ' ' . ($user->tertiary_role ?? ''), 'broadcaster') !== false;
        if (!$isBroadcastHead && !in_array($user->role, ['eic', 'admin'])) {
            return response()->json(['message' => 'Only the Head Broadcasters can publish a video directly.'], 403);
        }

        $rules = [
            'title'          => 'required|string|max:500',
            'excerpt'        => 'required|string|max:1000',
            'video_url'      => 'required|url:http,https|max:500',
            'video_category' => 'required|in:' . implode(',', Article::VIDEO_CATEGORIES),
            'cover_image'    => 'nullable|string',
            'published_at'   => 'nullable|date|before_or_equal:now',
            'credits'        => 'nullable|array',
        ];
        foreach (array_keys(ArticleCredit::ROLES) as $role) {
            $rules["credits.$role"] = 'nullable|array';
            $rules["credits.$role.*"] = 'integer|exists:users,id';
        }
        $validated = $request->validate($rules);

        $credits = $validated['credits'] ?? [];
        $publishedAt = isset($validated['published_at']) ? Carbon::parse($validated['published_at']) : now();

        // The first reporter is the on-screen presenter; otherwise whoever is publishing owns it
        $article = new Article([
            'title'          => $validated['title'],
            'content'        => $validated['excerpt'],
            'excerpt'        => $validated['excerpt'],
            'author_id'      => ($credits['reporter'][0] ?? null) ?: $user->id,
            'section_id'     => \App\Models\Section::whereRaw('LOWER(name) = ?', ['video'])->value('id'),
            'type'           => Article::TYPE_VIDEO,
            'status'         => Article::STATUS_PUBLISHED,
            'video_url'      => $validated['video_url'],
            'video_category' => $validated['video_category'],
            'cover_image'    => $validated['cover_image'] ?? null,
            'submitted_at'   => $publishedAt,
            'endorsed_at'    => $publishedAt,
            'approved_at'    => $publishedAt,
            'published_at'   => $publishedAt,
        ]);
        // A past video belongs to the academic year it was published in
        $article->created_at = $publishedAt;
        $article->updated_at = $publishedAt;
        $article->save();

        $this->syncCredits($article, $credits);
        Activity::record($user, 'Published a video directly', $article);

        return response()->json($article->load(['author', 'section', 'credits.user']), 201);
    }

    /**
     * Publish an article straight away ("Publish Automatic/Past Article"), skipping the
     * writer → editor → copyreader → EIC review. For urgent stories and for back-filling
     * past issues. The chosen writer becomes the author and the PJ/Artist gets a
     * completed illustration task, so both see it under their own work.
     */
    public function publishDirect(Request $request)
    {
        $user = $request->user();
        if (!in_array($user->role, ['section_editor', 'eic', 'admin'])) {
            return response()->json(['message' => 'Only editors can publish an article directly.'], 403);
        }

        $validated = $request->validate([
            'title'          => 'required|string|max:500',
            'content'        => 'required|string',
            'section_id'     => 'required|exists:sections,id',
            'author_id'      => 'required|exists:users,id',
            'artist_id'      => 'nullable|exists:users,id',
            'cover_image'    => 'nullable|string',
            'media_files'    => 'nullable|array',
            'media_files.*'  => 'nullable|string',
            'published_at'   => 'nullable|date|before_or_equal:now',
        ]);

        $validated['content'] = Html::clean($validated['content']);
        $publishedAt = isset($validated['published_at']) ? Carbon::parse($validated['published_at']) : now();
        $plain = trim(preg_replace('/\s+/', ' ', strip_tags(str_replace(['</p>', '<br>', '<br/>', '</div>'], ' ', $validated['content']))));

        $article = new Article([
            'title'        => $validated['title'],
            'content'      => $validated['content'],
            'excerpt'      => mb_substr($plain, 0, 300),
            'author_id'    => $validated['author_id'],
            'section_id'   => $validated['section_id'],
            'type'         => Article::TYPE_ARTICLE,
            'status'       => Article::STATUS_PUBLISHED,
            'word_count'   => $plain === '' ? 0 : count(preg_split('/\s+/', $plain)),
            'cover_image'  => $validated['cover_image'] ?? null,
            'media_files'  => $validated['media_files'] ?? [],
            'submitted_at' => $publishedAt,
            'endorsed_at'  => $publishedAt,
            'approved_at'  => $publishedAt,
            'published_at' => $publishedAt,
        ]);
        // A past article belongs to the academic year it was published in
        $article->created_at = $publishedAt;
        $article->updated_at = $publishedAt;
        $article->save();

        if (!empty($validated['artist_id'])) {
            Task::create([
                'title'        => $article->title,
                'description'  => 'Published directly without a review workflow.',
                'article_id'   => $article->id,
                'assignee_id'  => $validated['artist_id'],
                'assigned_by'  => $user->id,
                'section_id'   => $article->section_id,
                'type'         => Task::TYPE_ILLUSTRATION,
                'status'       => Task::STATUS_COMPLETED,
                'completed_at' => $publishedAt,
            ]);
        }

        Activity::record($user, 'Published an article directly', $article);

        foreach (array_filter([$validated['author_id'], $validated['artist_id'] ?? null]) as $recipientId) {
            if ((int) $recipientId === $user->id) continue;
            Notification::create([
                'user_id' => $recipientId,
                'title'   => 'Article Published',
                'message' => "'{$article->title}' was published with you credited on it.",
                'type'    => Notification::TYPE_GENERAL,
                'data'    => ['article_id' => $article->id],
            ]);
        }

        return response()->json($article->load(['author', 'section', 'tasks.assignee']), 201);
    }

    /**
     * Replace the credits on a video: {credits: {reporter: [ids], scriptwriter: [ids],
     * videographer: [ids], video_editor: [ids]}}. Set by the Head / Assistant Head
     * Broadcaster (or the EIC / admin) before the video goes to the EIC.
     */
    public function setCredits(Request $request, Article $article)
    {
        $user = $request->user();
        if (!in_array($user->role, ['section_editor', 'eic', 'admin'])) {
            return response()->json(['message' => 'Not authorized to credit this video.'], 403);
        }
        if ($article->type !== Article::TYPE_VIDEO) {
            return response()->json(['message' => 'Only videos can be credited.'], 422);
        }

        $roles = array_keys(ArticleCredit::ROLES);
        $rules = ['credits' => 'required|array'];
        foreach ($roles as $role) {
            $rules["credits.$role"] = 'nullable|array';
            $rules["credits.$role.*"] = 'integer|exists:users,id';
        }
        $validated = $request->validate($rules);

        $this->syncCredits($article, $validated['credits']);

        Activity::record($user, 'Updated video credits', $article);

        return response()->json($article->fresh()->load(['author', 'section', 'tasks.assignee', 'credits.user']));
    }

    /** Delete an article */
    public function destroy(Request $request, Article $article)
    {
        $user = $request->user();
        $isPrivileged = in_array($user->role, ['eic', 'admin']);
        $isOwner = $article->author_id === $user->id;

        if ($article->status === Article::STATUS_PUBLISHED) {
            if (!$isPrivileged) {
                return response()->json(['message' => 'Only the Editor-in-Chief can delete a published article.'], 403);
            }
        } elseif (!$isOwner && !$isPrivileged) {
            return response()->json(['message' => 'Not authorized to delete this article.'], 403);
        }

        Activity::record($user, 'Deleted an article', $article);
        $article->tasks()->delete();
        $article->delete();
        return response()->json(['message' => 'Article deleted successfully.']);
    }

    /** Flip any past-due scheduled articles to published (lazy scheduler — no cron required) */
    private function publishDueSchedules(): void
    {
        Article::publishDue();
    }

    /** Staff Writer submits article to Section Editor */
    public function submit(Request $request, Article $article)
    {
        if (!$this->canWorkOn($request->user(), $article)) {
            return response()->json(['message' => 'You are not allowed to submit this article.'], 403);
        }

        if ($article->type !== Article::TYPE_VIDEO && (!$article->cover_image || empty($article->media_files))) {
            return response()->json(['message' => 'A thumbnail and at least one media upload are required before this article can be submitted for review.'], 422);
        }

        $article->update([
            'status'       => Article::STATUS_SUBMITTED,
            'submitted_at' => now(),
        ]);
        Activity::record($request->user(), 'Submitted an article', $article);

        // Notify section editors
        $this->notifySectionEditors($article, 'Article Submitted', "'{$article->title}' has been submitted for review.");

        return response()->json($article->load(['author', 'section']));
    }

    /** Section Editor endorses article to EIC */
    public function endorse(Request $request, Article $article)
    {
        if (!$request->user()->isReviewer()) {
            return response()->json(['message' => 'Only editors and copyreaders can endorse an article.'], 403);
        }

        $request->validate(['editor_notes' => 'nullable|string']);

        $article->update([
            'status'       => Article::STATUS_ENDORSED,
            'endorsed_at'  => now(),
            'editor_notes' => $request->editor_notes,
        ]);
        Activity::record($request->user(), 'Endorsed an article', $article);

        // Notify EICs
        $this->notifyEICs($article, 'Article Endorsed', "'{$article->title}' has been endorsed and is awaiting your approval.");

        // Notify author
        Notification::create([
            'user_id' => $article->author_id,
            'title'   => 'Article Endorsed',
            'message' => "Your article '{$article->title}' has been endorsed to the Editor-in-Chief.",
            'type'    => Notification::TYPE_ARTICLE_ENDORSED,
            'data'    => ['article_id' => $article->id],
        ]);

        return response()->json($article->load(['author', 'section']));
    }

    /** EIC approves article */
    public function approve(Request $request, Article $article)
    {
        if (!in_array($request->user()->role, ['eic', 'admin'], true)) {
            return response()->json(['message' => 'Only the Editor-in-Chief can approve an article.'], 403);
        }

        $article->update([
            'status'      => Article::STATUS_APPROVED,
            'approved_at' => now(),
        ]);
        Activity::record($request->user(), 'Approved an article', $article);

        Notification::create([
            'user_id' => $article->author_id,
            'title'   => 'Article Approved',
            'message' => "Your article '{$article->title}' has been approved by the Editor-in-Chief!",
            'type'    => Notification::TYPE_ARTICLE_APPROVED,
            'data'    => ['article_id' => $article->id],
        ]);

        return response()->json($article->load(['author', 'section']));
    }

    /** EIC or Section Editor rejects article */
    public function reject(Request $request, Article $article)
    {
        if (!$request->user()->isEditor()) {
            return response()->json(['message' => 'Only editors can reject an article.'], 403);
        }

        $request->validate(['rejection_reason' => 'required|string']);

        $article->update([
            'status'           => Article::STATUS_REJECTED,
            'rejected_at'      => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);
        Activity::record($request->user(), 'Rejected an article', $article);

        Notification::create([
            'user_id' => $article->author_id,
            'title'   => 'Article Rejected',
            'message' => "Your article '{$article->title}' was rejected. Reason: {$request->rejection_reason}",
            'type'    => Notification::TYPE_ARTICLE_REJECTED,
            'data'    => ['article_id' => $article->id],
        ]);

        // Send every linked task (writer, artist, etc.) back to "returned" so it reappears
        // in the assignee's Ongoing column instead of staying stuck under Submitted.
        // The writer's (or a video presenter's) task was already marked completed when the article moved on
        // (the Section Editor sending it to the Copyreader, or the Head Broadcaster sending a video to the EIC),
        // so it has to be reopened here too or the writer never sees the return and can't revise it.
        // Other finished crew tasks (artist, layout...) stay completed.
        $tasks = Task::where('article_id', $article->id)
            ->where(function ($q) {
                $q->where('status', '!=', Task::STATUS_COMPLETED)
                    ->orWhere('type', Task::TYPE_WRITING);
            })
            ->get();

        foreach ($tasks as $task) {
            // A returned video only goes back to its presenter; the crew (videographer,
            // video editor) has nothing to revise.
            if ($article->type === Article::TYPE_VIDEO && $task->type !== Task::TYPE_WRITING) {
                continue;
            }

            $task->update([
                'status'           => Task::STATUS_RETURNED,
                'completed_at'     => null,
                'notes'            => $this->withRevisionNotes($task->notes, $request->rejection_reason),
                'returned_by_role' => $this->returnerRoleLabel($request->user()),
            ]);

            if ($task->assignee_id !== $article->author_id) {
                Notification::create([
                    'user_id' => $task->assignee_id,
                    'title'   => 'Task Returned for Revision',
                    'message' => "The article '{$article->title}' was returned for revision. Notes: {$request->rejection_reason}",
                    'type'    => Notification::TYPE_TASK_RETURNED,
                    'data'    => ['task_id' => $task->id, 'article_id' => $article->id],
                ]);
            }
        }

        return response()->json($article->load(['author', 'section']));
    }

    /**
     * Merge a revision reason into a task's notes without losing the structured
     * fields (Section, Coverage, Media Artist, etc.) other views parse out of it.
     */
    private function withRevisionNotes(?string $notes, string $reason): string
    {
        $base = trim(preg_replace('/\s*\|?\s*Revision Notes:\s*[^|]*/i', '', $notes ?? ''));
        $segment = 'Revision Notes: ' . $reason;
        return $base !== '' ? "{$base} | {$segment}" : $segment;
    }

    /**
     * Which editorial-review stage sent a task back, so the writer's workspace
     * can route the revision note into the matching Section Editor / Copyreader / EIC box.
     */
    private function returnerRoleLabel($user): string
    {
        if (!$user) return '';
        if ($user->role === 'eic') return 'eic';
        if ($user->role === 'section_editor') return 'section_editor';
        if (in_array('Copyreader', [$user->secondary_role, $user->tertiary_role], true)
            || in_array('Copy Editor', [$user->secondary_role, $user->tertiary_role], true)) {
            return 'copyreader';
        }
        return $user->role ?? '';
    }

    /**
     * Upload one or more media images for an article.
     * Accepts multipart/form-data with field "files[]".
     * Returns an array of public storage URLs.
     */
    public function uploadMedia(Request $request)
    {
        $request->validate([
            'files'   => 'required|array|min:1|max:3',
            'files.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        $urls = [];
        foreach ($request->file('files') as $file) {
            $path = Images::store($file, 'article-media', Images::PHOTO);
            $urls[] = '/storage/' . $path;
        }

        return response()->json(['urls' => $urls], 201);
    }

    private function notifySectionEditors(Article $article, string $title, string $message): void
    {
        // (users no longer have a section_id column, so every section editor is notified; so is an
        // Editor-in-Chief who is a section editor too, since it reaches their Endorsements tab)
        $editors = \App\Models\User::where('is_active', true)->where(function ($q) {
            $q->where('role', 'section_editor')
              ->orWhere(fn ($eic) => $eic->where('role', 'eic')->where(fn ($t) => $t
                  ->whereIn('secondary_role', \App\Models\User::SECTION_EDITOR_TITLES)
                  ->orWhereIn('tertiary_role', \App\Models\User::SECTION_EDITOR_TITLES)));
        });

        // Videos are reviewed only by the Head / Assistant Head Broadcaster
        if ($article->type === Article::TYPE_VIDEO) {
            $editors->where(function ($q) {
                $q->where('secondary_role', 'like', '%Broadcaster%')
                  ->orWhere('tertiary_role', 'like', '%Broadcaster%');
            });
        }

        $editors = $editors->get();

        foreach ($editors as $editor) {
            Notification::create([
                'user_id' => $editor->id,
                'title'   => $title,
                'message' => $message,
                'type'    => Notification::TYPE_ARTICLE_SUBMITTED,
                'data'    => ['article_id' => $article->id],
            ]);
        }
    }

    private function notifyEICs(Article $article, string $title, string $message): void
    {
        $eics = \App\Models\User::where('role', 'eic')->where('is_active', true)->get();
        foreach ($eics as $eic) {
            Notification::create([
                'user_id' => $eic->id,
                'title'   => $title,
                'message' => $message,
                'type'    => Notification::TYPE_ARTICLE_ENDORSED,
                'data'    => ['article_id' => $article->id],
            ]);
        }
    }
}
