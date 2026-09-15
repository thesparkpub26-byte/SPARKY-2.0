<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Activity;
use App\Models\Notification;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /** List tasks with optional filters */
    public function index(Request $request)
    {
        $query = Task::with(['assignee', 'assignedBy', 'section', 'article']);

        if ($request->has('assignee_id')) {
            $query->where('assignee_id', $request->assignee_id);
        }
        if ($request->has('assigned_by')) {
            $query->where('assigned_by', $request->assigned_by);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('section_id')) {
            $query->where('section_id', $request->section_id);
        }
        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

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
            'type'                 => 'nullable|in:writing,illustration,photography,layout,editing',
            'priority'             => 'nullable|in:low,medium,high,urgent',
            'deadline'             => 'nullable|date',
            'notes'                => 'nullable|string',
            'monitoring_sheet_url' => 'nullable|url',
            'word_count_target'    => 'nullable|integer|min:0',
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
        $validated = $request->validate([
            'title'                => 'sometimes|string|max:500',
            'description'          => 'nullable|string',
            'article_id'           => 'nullable|exists:articles,id',
            'assignee_id'          => 'sometimes|exists:users,id',
            'section_id'           => 'nullable|exists:sections,id',
            'type'                 => 'nullable|in:writing,illustration,photography,layout,editing',
            'priority'             => 'nullable|in:low,medium,high,urgent',
            'deadline'             => 'nullable|date',
            'notes'                => 'nullable|string',
            'monitoring_sheet_url' => 'nullable|url',
            'word_count_target'    => 'nullable|integer|min:0',
        ]);

        $task->update($validated);
        Activity::record($request->user(), 'Updated a task', $task);

        return response()->json($task->load(['assignee', 'assignedBy', 'section', 'article']));
    }

    /** Delete a task */
    public function destroy(Task $task)
    {
        Activity::record(request()->user(), 'Deleted a task', $task);
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully.']);
    }

    /** Staff submits completed task */
    public function submit(Request $request, Task $task)
    {
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
        $request->validate(['notes' => 'required|string']);

        $task->update([
            'status' => Task::STATUS_RETURNED,
            'notes'  => $request->notes,
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
}
