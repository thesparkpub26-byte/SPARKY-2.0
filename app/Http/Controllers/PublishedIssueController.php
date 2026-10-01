<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\PublishedIssue;
use App\Support\PublicCache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublishedIssueController extends Controller
{
    /** Staff: lists all published issues (PDFs) with their uploader, newest first. */
    public function index()
    {
        return response()->json(PublishedIssue::with('uploader')->latest()->get());
    }

    /**
     * Public: published issues for the reader site, newest first. Optional ?limit= (home page uses 3);
     * with ?page= the list is paginated (6 a page) and wrapped with the paging info.
     */
    public function latest(Request $request)
    {
        return response()->json(PublicCache::remember($request, 'issues', ['limit', 'page'], fn () => $this->latestList($request)));
    }

    /** Builds the public list of published issues (optionally limited or paginated) for the reader site. */
    private function latestList(Request $request): array
    {
        $query = PublishedIssue::latest('created_at')->latest('id');
        $toIssue = fn (PublishedIssue $i) => [
            'id'         => $i->id,
            'title'      => $i->title,
            'pdf_url'    => $i->pdf_path ? '/storage/' . $i->pdf_path : null,
            'created_at' => $i->created_at?->toIso8601String(),
        ];

        if ($request->filled('page')) {
            $page = $query->paginate(6);

            return [
                'data'         => $page->getCollection()->map($toIssue)->values()->all(),
                'current_page' => $page->currentPage(),
                'last_page'    => $page->lastPage(),
                'total'        => $page->total(),
            ];
        }

        if ($request->filled('limit')) {
            $query->limit(max(1, min(50, $request->integer('limit'))));
        }

        return $query->get()->map($toIssue)->all();
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

    /** Uploads a new published issue PDF with its title and date (EIC and admin only). */
    public function store(Request $request)
    {
        if (!in_array($request->user()->role, ['eic', 'admin'])) {
            return response()->json(['message' => 'Not authorized to upload published issues.'], 403);
        }

        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            // Kept under this server's 40M php.ini upload limit.
            'pdf'          => 'required|file|mimes:pdf|max:35840',
            'published_at' => 'nullable|date|before_or_equal:now',
        ]);

        $path = $request->file('pdf')->store('published-issues', 'public');
        $publishedAt = isset($validated['published_at']) ? Carbon::parse($validated['published_at']) : now();

        $issue = new PublishedIssue([
            'title'        => $validated['title'],
            'pdf_path'     => $path,
            'uploaded_by'  => $request->user()->id,
            'published_at' => $publishedAt,
        ]);
        // A backdated issue is filed and sorted under its actual date, not the upload time
        $issue->created_at = $publishedAt;
        $issue->updated_at = $publishedAt;
        $issue->save();

        Activity::record($request->user(), 'Uploaded a published issue', $issue);

        return response()->json($issue->load('uploader'), 201);
    }

    /** Returns one published issue with its uploader. */
    public function show(PublishedIssue $issue)
    {
        return response()->json($issue->load('uploader'));
    }

    /** Edits a published issue's title, date or PDF (EIC and admin only). */
    public function update(Request $request, PublishedIssue $issue)
    {
        if (!in_array($request->user()->role, ['eic', 'admin'])) {
            return response()->json(['message' => 'Not authorized to edit published issues.'], 403);
        }

        $validated = $request->validate([
            'title'        => 'sometimes|required|string|max:255',
            // Kept under this server's 40M php.ini upload limit.
            'pdf'          => 'nullable|file|mimes:pdf|max:35840',
            'published_at' => 'nullable|date|before_or_equal:now',
        ]);

        if ($request->hasFile('pdf')) {
            if ($issue->pdf_path) {
                Storage::disk('public')->delete($issue->pdf_path);
            }
            $validated['pdf_path'] = $request->file('pdf')->store('published-issues', 'public');
            unset($validated['pdf']);
        }

        if (isset($validated['published_at'])) {
            $publishedAt = Carbon::parse($validated['published_at']);
            $validated['published_at'] = $publishedAt;
            // Re-filed under its corrected date, so it sorts correctly for readers
            $issue->created_at = $publishedAt;
            $issue->updated_at = $publishedAt;
        }

        $issue->update($validated);
        Activity::record($request->user(), 'Updated a published issue', $issue);

        return response()->json($issue->load('uploader'));
    }

    /** Deletes a published issue and its PDF (EIC and admin only). */
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
