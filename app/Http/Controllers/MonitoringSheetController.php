<?php

namespace App\Http\Controllers;

use App\Models\MonitoringSheet;
use App\Models\MonitoringSheetEntry;
use App\Support\Html;
use Illuminate\Http\Request;

class MonitoringSheetController extends Controller
{
    public function show(MonitoringSheet $monitoringSheet)
    {
        return response()->json($monitoringSheet->load('pressWork', 'entries'));
    }

    public function storeEntry(Request $request, MonitoringSheet $monitoringSheet)
    {
        $validated = $request->validate([
            'topic' => 'nullable|string|max:500',
            'section' => 'required|string|max:50',
            'article_type' => 'nullable|string|max:100',
            'medium' => 'nullable|string|max:50',
            'writer_assigned' => 'nullable|string|max:255',
            'media_type' => 'nullable|string|max:50',
            'artist_assigned' => 'nullable|string|max:255',
            'interview_completed' => 'boolean',
            'has_files' => 'boolean',
            'current_status' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'priority' => 'nullable|in:Low,Moderate,High,Urgent',
            'deadline' => 'nullable|date',
            'deadline_time' => 'nullable|date_format:H:i',
            'article_headline' => 'nullable|string|max:500',
            'article_author' => 'nullable|string|max:255',
            'article_content' => 'nullable|string',
        ]);

        if (array_key_exists('article_content', $validated)) {
            $validated['article_content'] = Html::clean($validated['article_content']);
        }

        $entry = $monitoringSheet->entries()->create($validated);
        
        // Notify EICs and assigned contributors about new monitoring sheet task
        try {
            $taskTitle = $entry->topic ?: 'New Task';
            $writerText = $entry->writer_assigned ? " assigned to {$entry->writer_assigned}" : '';
            $notifiedUserIds = [];

            // 1. Notify EICs
            $eics = \App\Models\User::where('role', 'eic')->get();
            foreach ($eics as $eic) {
                \App\Models\Notification::create([
                    'user_id' => $eic->id,
                    'title'   => 'Press Work Task Added',
                    'message' => "Task '{$taskTitle}'{$writerText} in {$entry->section} ({$monitoringSheet->title}).",
                    'type'    => 'press_work_update',
                    'data'    => ['sheet_id' => $monitoringSheet->id, 'entry_id' => $entry->id],
                ]);
                $notifiedUserIds[] = $eic->id;
            }

            // 2. Notify Assigned Writer
            if (!empty($entry->writer_assigned)) {
                $writer = \App\Models\User::where('name', $entry->writer_assigned)->first();
                if ($writer && !in_array($writer->id, $notifiedUserIds)) {
                    \App\Models\Notification::create([
                        'user_id' => $writer->id,
                        'title'   => 'Press Work Task Assigned',
                        'message' => "You have been assigned as writer for '{$taskTitle}' in {$entry->section} ({$monitoringSheet->title}).",
                        'type'    => 'press_work_update',
                        'data'    => ['sheet_id' => $monitoringSheet->id, 'entry_id' => $entry->id],
                    ]);
                    $notifiedUserIds[] = $writer->id;
                }
            }

            // 3. Notify Assigned Artist/PJ
            if (!empty($entry->artist_assigned) && $entry->artist_assigned !== 'No Graphics' && $entry->artist_assigned !== 'N/A') {
                $artist = \App\Models\User::where('name', $entry->artist_assigned)->first();
                if ($artist && !in_array($artist->id, $notifiedUserIds)) {
                    \App\Models\Notification::create([
                        'user_id' => $artist->id,
                        'title'   => 'Press Work Media Assigned',
                        'message' => "You have been assigned to '{$taskTitle}' ({$entry->media_type}) in {$entry->section} ({$monitoringSheet->title}).",
                        'type'    => 'press_work_update',
                        'data'    => ['sheet_id' => $monitoringSheet->id, 'entry_id' => $entry->id],
                    ]);
                }
            }
        } catch (\Throwable $e) {}

        return response()->json($entry, 201);
    }

    public function updateEntry(Request $request, MonitoringSheet $monitoringSheet, MonitoringSheetEntry $entry)
    {
        if ($entry->monitoring_sheet_id !== $monitoringSheet->id) {
            return response()->json(['error' => 'Entry does not belong to this monitoring sheet'], 403);
        }

        $validated = $request->validate([
            'topic' => 'nullable|string|max:500',
            'section' => 'nullable|string|max:50',
            'article_type' => 'nullable|string|max:100',
            'medium' => 'nullable|string|max:50',
            'writer_assigned' => 'nullable|string|max:255',
            'media_type' => 'nullable|string|max:50',
            'artist_assigned' => 'nullable|string|max:255',
            'interview_completed' => 'nullable|boolean',
            'has_files' => 'nullable|boolean',
            'current_status' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'priority' => 'nullable|in:Low,Moderate,High,Urgent',
            'deadline' => 'nullable|date',
            'deadline_time' => 'nullable|date_format:H:i',
            'article_headline' => 'nullable|string|max:500',
            'article_author' => 'nullable|string|max:255',
            'article_content' => 'nullable|string',
        ]);

        if (array_key_exists('article_content', $validated)) {
            $validated['article_content'] = Html::clean($validated['article_content']);
        }

        $oldStatus = $entry->current_status;
        $entry->update($validated);
        
        // Notify EICs on status update
        if (!empty($validated['current_status']) && $validated['current_status'] !== $oldStatus) {
            try {
                $eics = \App\Models\User::where('role', 'eic')->get();
                $taskTitle = $entry->topic ?: 'Task';
                foreach ($eics as $eic) {
                    \App\Models\Notification::create([
                        'user_id' => $eic->id,
                        'title'   => 'Press Work Status Updated',
                        'message' => "Task '{$taskTitle}' updated to '{$validated['current_status']}' in {$monitoringSheet->title}.",
                        'type'    => 'press_work_update',
                        'data'    => ['sheet_id' => $monitoringSheet->id, 'entry_id' => $entry->id],
                    ]);
                }
            } catch (\Throwable $e) {}
        }
        
        return response()->json($entry);
    }

    public function deleteEntry(MonitoringSheet $monitoringSheet, MonitoringSheetEntry $entry)
    {
        if ($entry->monitoring_sheet_id !== $monitoringSheet->id) {
            return response()->json(['error' => 'Entry does not belong to this monitoring sheet'], 403);
        }

        $entry->delete();
        
        return response()->json(['message' => 'Entry deleted successfully']);
    }

    public function uploadArticle(Request $request, MonitoringSheet $monitoringSheet, MonitoringSheetEntry $entry)
    {
        if ($entry->monitoring_sheet_id !== $monitoringSheet->id) {
            return response()->json(['error' => 'Entry does not belong to this monitoring sheet'], 403);
        }

        $validated = $request->validate([
            'headline' => 'required|string|max:500',
            'author' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $entry->update([
            'article_headline' => $validated['headline'],
            'article_author' => $validated['author'],
            'article_content' => Html::clean($validated['content']),
        ]);
        
        try {
            $eics = \App\Models\User::where('role', 'eic')->get();
            foreach ($eics as $eic) {
                \App\Models\Notification::create([
                    'user_id' => $eic->id,
                    'title'   => 'Article Draft Uploaded',
                    'message' => "Draft '{$validated['headline']}' uploaded by {$validated['author']} for '{$entry->topic}'.",
                    'type'    => 'press_work_update',
                    'data'    => ['sheet_id' => $monitoringSheet->id, 'entry_id' => $entry->id],
                ]);
            }
        } catch (\Throwable $e) {}

        return response()->json(['message' => 'Article uploaded successfully', 'entry' => $entry]);
    }
}
