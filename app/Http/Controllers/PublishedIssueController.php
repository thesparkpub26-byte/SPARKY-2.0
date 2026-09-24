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

    /** Public: published issues for the reader site, newest first. Optional ?limit= (home page uses 3). */
    public function latest(Request $request)
    {
        $query = PublishedIssue::latest();
        if ($request->filled('limit')) {
            $query->limit(max(1, min(50, $request->integer('limit'))));
        }

        return response()->json(
            $query->get(['id', 'title', 'pdf_path', 'created_at'])
                ->map(fn (PublishedIssue $i) => [
                    'id'         => $i->id,
                    'title'      => $i->title,
                    'pdf_url'    => $i->pdf_path ? '/storage/' . $i->pdf_path : null,
                    'created_at' => $i->created_at?->toIso8601String(),
                ])
        );
    }

    /** Public: a single issue (no uploader details) so readers can open the booklet. */
    public function publicShow(PublishedIssue $issue)
    {
        return response()->json([
            'id'      => $issue->id,
            'title'   => $issue->title,
            'pdf_url' => $issue->pdf_path ? '/storage/' . $issue->pdf_path : null,
        ]);
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
