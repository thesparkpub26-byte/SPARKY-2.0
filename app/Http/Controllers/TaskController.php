<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Article;
use App\Models\Activity;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /** List tasks with optional filters */
    public function index(Request $request)
    {
        $query = Task::with(['assignee', 'assignedBy', 'section', 'article']);

        $this->filterBy(
            $query,
            $request,
            ['status' => 'status', 'priority' => 'priority', 'type' => 'type'],
            ['assignee_id' => 'assignee_id', 'assigned_by' => 'assigned_by', 'section_id' => 'section_id'],
        );

        return response()->json($query->orderByDesc('created_at')->get());
    }

    /** Get a single task */
    public function show(Task $task)
    {
        return response()->json($task->load(['assignee', 'assignedBy', 'section', 'article.author']));
    }

    /** Assign a new task */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'                => 'required|string|max:500',
            'description'          => 'nullable|string',
            'article_id'           => 'nullable|exists:articles,id',
            'assignee_id'          => 'required|exists:users,id',
            'section_id'           => 'nullable|exists:sections,id',
            'type'                 => 'nullable|in:writing,illustration,photography,layout,editing,videography,video_editing',
            'priority'             => 'nullable|in:low,medium,high,urgent',
            'deadline'             => 'nullable|date',
            'notes'                => 'nullable|string',
        ]);

        $validated['assigned_by'] = $request->user()->id;
        $task = Task::create($validated);
        Activity::record($request->user(), 'Assigned a task', $task);

        // Notify the assignee
        Notification::create([
            'user_id' => $task->assignee_id,
            'title'   => 'New Task Assigned',
            'message' => "You have been assigned a new task: '{$task->title}'.",
            'type'    => Notification::TYPE_TASK_ASSIGNED,
            'data'    => ['task_id' => $task->id],
        ]);

        return response()->json($task->load(['assignee', 'assignedBy', 'section', 'article']), 201);
    }

    /** Update task details */
    public function update(Request $request, Task $task)
    {
        $user = $request->user();
        $validated = $request->validate([
            'title'                => 'sometimes|string|max:500',
            'description'          => 'nullable|string',
            'article_id'           => 'nullable|exists:articles,id',
            'assignee_id'          => 'sometimes|exists:users,id',
            'section_id'           => 'nullable|exists:sections,id',
            'type'                 => 'nullable|in:writing,illustration,photography,layout,editing,videography,video_editing',
            'priority'             => 'nullable|in:low,medium,high,urgent',
            'status'               => 'nullable|in:pending,in_progress,submitted,returned,completed',
            'deadline'             => 'nullable|date',
            'notes'                => 'nullable|string',
        ]);

        // Staff also link crew tasks to their article, so edits stay open; handing a task to someone
        // else or marking it complete belongs to whoever set it or reviews work.
        $reassigns = isset($validated['assignee_id']) && (int) $validated['assignee_id'] !== (int) $task->assignee_id;
        $completes = ($validated['status'] ?? null) === Task::STATUS_COMPLETED && $task->status !== Task::STATUS_COMPLETED;
        if (($reassigns || $completes) && !$this->isReviewerOf($user, $task)) {
            return response()->json(['message' => 'Only the person who assigned this task or an editor can do that.'], 403);
        }

        $task->update($validated);
        Activity::record($request->user(), 'Updated a task', $task);

        return response()->json($task->load(['assignee', 'assignedBy', 'section', 'article']));
    }

    /** Delete a task */
    public function destroy(Task $task)
    {
        $user = request()->user();
        if ($task->assigned_by !== $user->id && !$user->isEditor()) {
            return response()->json(['message' => 'Only the person who assigned this task or an editor can delete it.'], 403);
        }

        Activity::record(request()->user(), 'Deleted a task', $task);

        $articleId = $task->article_id;
        $title = $task->title;
        $assigneeId = $task->assignee_id;

        $wasWritingTask = $task->type === Task::TYPE_WRITING;

        // The writer's task is the assignment itself: cancelling it takes the artist / crew tasks made with it along
        $crewTasks = $wasWritingTask ? $this->crewTasksOf($task) : collect();

        $task->delete();

        foreach ($crewTasks as $crew) {
            Notification::create([
                'user_id' => $crew->assignee_id,
                'title'   => 'Assignment Cancelled',
                'message' => "The assignment '{$title}' was cancelled, so your task '{$crew->title}' was removed.",
                'type'    => Notification::TYPE_GENERAL,
                'data'    => ['task_id' => $crew->id],
            ]);
            $crew->delete();
        }

        if ($articleId) {
            $article = Article::find($articleId);

            // Deleting a video's presenter task cancels the whole video assignment:
            // the submission and the crew's tasks go with it (unless it's already live).
            if ($article && $wasWritingTask && $article->type === Article::TYPE_VIDEO && $article->status !== Article::STATUS_PUBLISHED) {
                Task::where('article_id', $article->id)->delete();
                $article->delete();
                return response()->json(['message' => 'Task deleted successfully.']);
            }

            if ($article) {
                $otherTasksCount = Task::where('article_id', $article->id)->count();
                if ($otherTasksCount === 0) {
                    $article->delete();
                }
            }
        } else {
            // Also check for draft article created with matching title and author
            $matchingArticle = Article::where('title', $title)
                ->where('author_id', $assigneeId)
                ->where('status', 'draft')
                ->first();
            if ($matchingArticle) {
                $otherTasksCount = Task::where('article_id', $matchingArticle->id)->count();
                if ($otherTasksCount === 0) {
                    $matchingArticle->delete();
                }
            }
        }

        return response()->json(['message' => 'Task deleted successfully.']);
    }

    /** Staff submits completed task */
    public function submit(Request $request, Task $task)
    {
        $user = $request->user();
        if ($task->assignee_id !== $user->id && !$this->isReviewerOf($user, $task)) {
            return response()->json(['message' => 'Only the person this task is assigned to can submit it.'], 403);
        }

        $request->validate(['notes' => 'nullable|string']);

        $task->update([
            'status' => Task::STATUS_SUBMITTED,
            'notes'  => $request->notes ?? $task->notes,
        ]);
        Activity::record($request->user(), 'Submitted a task', $task);

        // Notify the task creator
        Notification::create([
            'user_id' => $task->assigned_by,
            'title'   => 'Task Submitted',
            'message' => "{$task->assignee->name} has submitted the task: '{$task->title}'.",
            'type'    => Notification::TYPE_TASK_SUBMITTED,
            'data'    => ['task_id' => $task->id],
        ]);

        return response()->json($task->load(['assignee', 'assignedBy', 'section', 'article']));
    }

    /** Editor returns task for revision */
    public function return(Request $request, Task $task)
    {
        if (!$this->isReviewerOf($request->user(), $task)) {
            return response()->json(['message' => 'Only the person who assigned this task or a reviewer can return it.'], 403);
        }

        $request->validate(['notes' => 'required|string']);

        $task->update([
            'status'           => Task::STATUS_RETURNED,
            'notes'            => $this->withRevisionNotes($task->notes, $request->notes),
            'returned_by_role' => $this->returnerRoleLabel($request->user()),
        ]);
        Activity::record($request->user(), 'Returned a task', $task);

        Notification::create([
            'user_id' => $task->assignee_id,
            'title'   => 'Task Returned for Revision',
            'message' => "Your task '{$task->title}' has been returned for revision. Notes: {$request->notes}",
            'type'    => Notification::TYPE_TASK_RETURNED,
            'data'    => ['task_id' => $task->id],
        ]);

        return response()->json($task->load(['assignee', 'assignedBy', 'section', 'article']));
    }

    /** Editor marks task as completed */
    public function complete(Task $task)
    {
        if (!$this->isReviewerOf(request()->user(), $task)) {
            return response()->json(['message' => 'Only the person who assigned this task or a reviewer can complete it.'], 403);
        }

        $task->update([
            'status'       => Task::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);
        Activity::record(request()->user(), 'Completed a task', $task);

        Notification::create([
            'user_id' => $task->assignee_id,
            'title'   => 'Task Marked Complete',
            'message' => "Your task '{$task->title}' has been marked as completed. Great work!",
            'type'    => Notification::TYPE_TASK_COMPLETED,
            'data'    => ['task_id' => $task->id],
        ]);

        return response()->json($task->load(['assignee', 'assignedBy', 'section', 'article']));
    }

    /**
     * The artist / crew tasks that belong to an assignment (the writer's task). The assign form creates them
     * unlinked and names each "<assignment title> (Visuals / Graphics)" and so on, straight after the writer's
     * task, so those are found by that name; once an article exists they are found through it as well.
     * Nothing is touched once the article is live: its artwork tasks are then part of the published record.
     */
    private function crewTasksOf(Task $assignment)
    {
        if ($assignment->article_id && Article::whereKey($assignment->article_id)->where('status', Article::STATUS_PUBLISHED)->exists()) {
            return collect();
        }

        $crewTypes = [Task::TYPE_ILLUSTRATION, Task::TYPE_PHOTOGRAPHY, Task::TYPE_VIDEOGRAPHY, Task::TYPE_VIDEO_EDITING];
        $titleMatches = array_map(fn ($suffix) => $assignment->title . $suffix, [' (Visuals / Graphics)', ' (Videography)', ' (Video Editing)']);

        return Task::whereIn('type', $crewTypes)
            ->where('id', '!=', $assignment->id)
            ->where(function ($q) use ($assignment, $titleMatches) {
                if ($assignment->article_id) {
                    $q->orWhere('article_id', $assignment->article_id);
                }
                // Fall back to a title + assigner match regardless of the crew task's own article_id —
                // duplicate saves used to link the writer's and the crew's task to different article
                // copies, which left the article_id check above unable to find its match.
                $q->orWhere(fn ($q) => $q
                    ->where('assigned_by', $assignment->assigned_by)
                    ->whereIn('title', $titleMatches)
                    ->where(fn ($q) => $q
                        ->whereNull('article_id')
                        ->orWhereDoesntHave('article', fn ($q) => $q->where('status', Article::STATUS_PUBLISHED))));
            })
            ->get();
    }

    /** The person who assigned the task, or an editor / copyreader (who review submitted work). */
    private function isReviewerOf(User $user, Task $task): bool
    {
        return $task->assigned_by === $user->id || $user->isReviewer();
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
}
