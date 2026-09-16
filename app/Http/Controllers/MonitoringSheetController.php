<?php

namespace App\Http\Controllers;

use App\Models\MonitoringSheet;
use App\Models\MonitoringSheetEntry;
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
            'storage_url' => 'nullable|string|max:500',
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

        $entry = $monitoringSheet->entries()->create($validated);
        
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
            'storage_url' => 'nullable|string|max:500',
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

        $entry->update($validated);
        
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
            'article_content' => $validated['content'],
        ]);
        
        return response()->json(['message' => 'Article uploaded successfully', 'entry' => $entry]);
    }
}
