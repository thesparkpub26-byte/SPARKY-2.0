<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Article;
use App\Models\ArticleVersion;
use App\Models\ReviewComment;
use App\Models\Task;
use Illuminate\Http\Request;

/** Reviewers highlight passages of an article and comment on them; the writer marks each one done. */
class ReviewCommentController extends Controller
{
    /** What an activity row is called on the article's Details tab (rows not listed here are left out). */
    private const ACTIVITY_LABELS = [
        'Left a comment'       => 'Left a Comment',
        'Resolved a comment'   => 'Marked a Comment Done',
        'Returned a task'      => 'Returned for Revision',
        'Rejected an article'  => 'Returned for Revision',
        'Submitted an article' => 'Submitted for Review',
        'Endorsed an article'  => 'Endorsed',
        'Approved an article'  => 'Approved',
    ];

    /** The comments on an article, oldest first. */
    public function index(Request $request, Article $article)
    {
        if (!$article->canBeWorkedOnBy($request->user())) {
            return response()->json(['message' => 'You are not allowed to see this article\'s review comments.'], 403);
        }

        return response()->json(
            $article->reviewComments()->with(['user:id,name,profile_picture', 'resolver:id,name'])->orderBy('id')->get()
        );
    }

    /** A reviewer comments on a highlighted passage. */
    public function store(Request $request, Article $article)
    {
        $user = $request->user();
        if (!$user->isReviewer()) {
            return response()->json(['message' => 'Only editors and copyreaders can comment on a submission.'], 403);
        }

        $validated = $request->validate([
            'quote'        => 'required|string|max:2000',
            'start_offset' => 'nullable|integer|min:0',
            'end_offset'   => 'nullable|integer|min:0',
            'body'         => 'required|string|max:1000',
        ]);

        $comment = $article->reviewComments()->create($validated + [
            'user_id'     => $user->id,
            'author_role' => $user->reviewRole(),
            'status'      => ReviewComment::STATUS_OPEN,
        ]);
        Activity::record($user, 'Left a comment', $article);

        return response()->json($comment->load(['user:id,name,profile_picture', 'resolver:id,name']), 201);
    }

    /** The writer (or whoever is working on the article) marks a comment done, or the reviewer takes it back. */
    public function update(Request $request, ReviewComment $comment)
    {
        $user = $request->user();
        $article = $comment->article;
        if (!$article->canBeWorkedOnBy($user)) {
            return response()->json(['message' => 'You are not allowed to change this comment.'], 403);
        }

        $validated = $request->validate(['status' => 'required|in:open,resolved']);

        // A comment that is already in the wanted state is left as it is
        if ($comment->status !== $validated['status']) {
            $resolving = $validated['status'] === ReviewComment::STATUS_RESOLVED;
            $comment->update([
                'status'      => $validated['status'],
                'resolved_by' => $resolving ? $user->id : null,
                'resolved_at' => $resolving ? now() : null,
            ]);
            Activity::record($user, $resolving ? 'Resolved a comment' : 'Reopened a comment', $article);
        }

        return response()->json($comment->fresh()->load(['user:id,name,profile_picture', 'resolver:id,name']));
    }

    /** The reviewer who wrote a comment can delete it while it is still open. */
    public function destroy(Request $request, ReviewComment $comment)
    {
        if ($comment->user_id !== $request->user()->id) {
            return response()->json(['message' => 'You can only delete your own comments.'], 403);
        }
        if ($comment->status !== ReviewComment::STATUS_OPEN) {
            return response()->json(['message' => 'A comment that was marked done can no longer be deleted.'], 422);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted.']);
    }

    /** The article's activity list and version history, for the Details tab. */
    public function details(Request $request, Article $article)
    {
        if (!$article->canBeWorkedOnBy($request->user())) {
            return response()->json(['message' => 'You are not allowed to see this article\'s details.'], 403);
        }

        $taskIds = Task::where('article_id', $article->id)
            ->whereIn('type', [Task::TYPE_WRITING, Task::TYPE_EDITING])
            ->pluck('id');

        $activities = Activity::excludingSessions()
            ->whereIn('action', array_keys(self::ACTIVITY_LABELS))
            ->where(fn ($q) => $q
                ->where(fn ($q) => $q->where('subject_type', Article::class)->where('subject_id', $article->id))
                ->orWhere(fn ($q) => $q->where('subject_type', Task::class)->whereIn('subject_id', $taskIds)))
            ->with('actor:id,name,role,secondary_role,tertiary_role')
            ->orderByDesc('id')
            ->get()
            ->map(fn (Activity $activity) => [
                'id'         => $activity->id,
                'user'       => $activity->actor?->name ?? 'Someone',
                'role'       => $activity->actor?->reviewRole(),
                'action'     => self::ACTIVITY_LABELS[$activity->action],
                'section'    => str_contains($activity->action, 'comment') ? 'Article Content' : '—',
                'created_at' => $activity->created_at,
            ])
            // Returning a task and rejecting the article are logged together: show one row, not two
            ->unique(fn (array $row) => $row['user'] . '|' . $row['action'] . '|' . $row['created_at']->format('Y-m-d H:i'))
            ->values();

        $versions = $article->versions()->with('user:id,name')->orderByDesc('version')->get();
        $latest = $versions->first()?->version;

        return response()->json([
            'activities' => $activities,
            'versions'   => $versions->map(fn (ArticleVersion $v) => [
                'id'         => $v->id,
                'version'    => $v->version,
                'label'      => $v->label,
                'user'       => $v->user?->name ?? 'Someone',
                'role'       => $v->author_role,
                'is_latest'  => $v->version === $latest,
                'created_at' => $v->created_at,
            ]),
        ]);
    }

    /** One saved version of the article, in full, for the Preview button. */
    public function version(Request $request, Article $article, ArticleVersion $version)
    {
        if (!$article->canBeWorkedOnBy($request->user())) {
            return response()->json(['message' => 'You are not allowed to see this version.'], 403);
        }
        if ($version->article_id !== $article->id) {
            return response()->json(['message' => 'That version belongs to another article.'], 404);
        }

        return response()->json($version->load('user:id,name'));
    }
}
