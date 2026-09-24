<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\PublishedIssue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublishedIssueController extends Controller
{
    public function index()
    {
        return response()->json(PublishedIssue::with('uploader')->latest()->get());
    }

    public function store(Request $request)
    {
        if (!in_array($request->user()->role, ['eic', 'admin'])) {
            return response()->json(['message' => 'Not authorized to upload published issues.'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            // Kept under this server's 40M php.ini upload limit.
            'pdf'   => 'required|file|mimes:pdf|max:35840',
        ]);

        $path = $request->file('pdf')->store('published-issues', 'public');

        $issue = PublishedIssue::create([
            'title'       => $validated['title'],
            'pdf_path'    => $path,
            'uploaded_by' => $request->user()->id,
        ]);

        Activity::record($request->user(), 'Uploaded a published issue', $issue);

        return response()->json($issue->load('uploader'), 201);
    }

    public function show(PublishedIssue $issue)
    {
        return response()->json($issue->load('uploader'));
    }

    public function update(Request $request, PublishedIssue $issue)
    {
        if (!in_array($request->user()->role, ['eic', 'admin'])) {
            return response()->json(['message' => 'Not authorized to edit published issues.'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            // Kept under this server's 40M php.ini upload limit.
            'pdf'   => 'nullable|file|mimes:pdf|max:35840',
        ]);

        if ($request->hasFile('pdf')) {
            if ($issue->pdf_path) {
                Storage::disk('public')->delete($issue->pdf_path);
            }
            $validated['pdf_path'] = $request->file('pdf')->store('published-issues', 'public');
            unset($validated['pdf']);
        }

        $issue->update($validated);
        Activity::record($request->user(), 'Updated a published issue', $issue);

        return response()->json($issue->load('uploader'));
    }

    public function destroy(Request $request, PublishedIssue $issue)
    {
        if (!in_array($request->user()->role, ['eic', 'admin'])) {
            return response()->json(['message' => 'Not authorized to delete published issues.'], 403);
        }

        Activity::record($request->user(), 'Deleted a published issue', $issue);

        if ($issue->pdf_path) {
            Storage::disk('public')->delete($issue->pdf_path);
        }
        $issue->delete();

        return response()->json(['message' => 'Issue deleted successfully.']);
    }
}
