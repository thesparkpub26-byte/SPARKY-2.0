<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Notification;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /** List articles with optional filters */
    public function index(Request $request)
    {
        $query = Article::with(['author', 'section']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('author_id')) {
            $query->where('author_id', $request->author_id);
        }
        if ($request->has('section_id')) {
            $query->where('section_id', $request->section_id);
        }
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        return response()->json($query->orderByDesc('created_at')->get());
    }

    /** Get a single article */
    public function show(Article $article)
    {
        return response()->json($article->load(['author', 'section', 'tasks.assignee']));
    }

    /** Create a new article */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'                 => 'required|string|max:500',
            'content'               => 'nullable|string',
            'excerpt'               => 'nullable|string|max:1000',
            'section_id'            => 'nullable|exists:sections,id',
            'type'                  => 'nullable|in:article,feature,opinion,photo_essay,illustration',
            'word_count'            => 'nullable|integer|min:0',
            'cover_image'           => 'nullable|string',
            'monitoring_sheet_url'  => 'nullable|url',
            'editor_notes'          => 'nullable|string',
        ]);

        $validated['author_id'] = $request->user()->id;
        $article = Article::create($validated);

        return response()->json($article->load(['author', 'section']), 201);
    }

    /** Update article content/metadata */
    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title'                 => 'sometimes|string|max:500',
            'content'               => 'nullable|string',
            'excerpt'               => 'nullable|string|max:1000',
            'section_id'            => 'nullable|exists:sections,id',
            'type'                  => 'nullable|in:article,feature,opinion,photo_essay,illustration',
            'word_count'            => 'nullable|integer|min:0',
            'cover_image'           => 'nullable|string',
            'monitoring_sheet_url'  => 'nullable|url',
            'editor_notes'          => 'nullable|string',
            'eic_notes'             => 'nullable|string',
        ]);

        $article->update($validated);

        return response()->json($article->load(['author', 'section']));
    }

    /** Delete an article */
    public function destroy(Article $article)
    {
        $article->delete();
        return response()->json(['message' => 'Article deleted successfully.']);
    }

    /** Staff Writer submits article to Section Editor */
    public function submit(Request $request, Article $article)
    {
        $article->update([
            'status'       => Article::STATUS_SUBMITTED,
            'submitted_at' => now(),
        ]);

        // Notify section editors
        $this->notifySectionEditors($article, 'Article Submitted', "'{$article->title}' has been submitted for review.");

        return response()->json($article->load(['author', 'section']));
    }

    /** Section Editor endorses article to EIC */
    public function endorse(Request $request, Article $article)
    {
        $request->validate(['editor_notes' => 'nullable|string']);

        $article->update([
            'status'       => Article::STATUS_ENDORSED,
            'endorsed_at'  => now(),
            'editor_notes' => $request->editor_notes,
        ]);

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
        $request->validate(['eic_notes' => 'nullable|string']);

        $article->update([
            'status'      => Article::STATUS_APPROVED,
            'approved_at' => now(),
            'eic_notes'   => $request->eic_notes,
        ]);

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
        $request->validate(['rejection_reason' => 'required|string']);

        $article->update([
            'status'           => Article::STATUS_REJECTED,
            'rejected_at'      => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        Notification::create([
            'user_id' => $article->author_id,
            'title'   => 'Article Rejected',
            'message' => "Your article '{$article->title}' was rejected. Reason: {$request->rejection_reason}",
            'type'    => Notification::TYPE_ARTICLE_REJECTED,
            'data'    => ['article_id' => $article->id],
        ]);

        return response()->json($article->load(['author', 'section']));
    }

    private function notifySectionEditors(Article $article, string $title, string $message): void
    {
        $editors = \App\Models\User::where('role', 'section_editor')
            ->where(function ($q) use ($article) {
                $q->where('section_id', $article->section_id)->orWhereNull('section_id');
            })->get();

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
        $eics = \App\Models\User::where('role', 'eic')->get();
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
