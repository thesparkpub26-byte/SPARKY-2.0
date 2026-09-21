<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function overview(Request $request)
    {
        abort_unless($request->user()->isAdmin(), 403);

        $recentActivities = Activity::with('actor:id,name,email,role,profile_picture')
            ->latest('created_at')
            ->limit(10)
            ->get()
            ->map(fn (Activity $activity) => [
                'id' => $activity->id,
                'action' => $activity->action,
                'subject' => $activity->subject_label,
                'user' => $activity->actor?->name ?? 'System',
                'role' => $activity->actor?->role ?? 'system',
                'avatar' => $activity->actor?->profile_picture_url,
                'created_at' => $activity->created_at,
            ]);

        return response()->json([
            'summary' => [
                'articles' => Article::count(),
                'users' => User::count(),
                'pending' => Article::whereIn('status', [
                    Article::STATUS_DRAFT,
                    Article::STATUS_SUBMITTED,
                    Article::STATUS_UNDER_REVIEW,
                    Article::STATUS_ENDORSED,
                ])->count(),
                'published' => Article::where('status', Article::STATUS_PUBLISHED)->count(),
            ],
            'user_activity' => [
                'active' => User::where('is_active', true)->latest('updated_at')->limit(5)->get(),
                'new' => User::latest('created_at')->limit(5)->get(),
                'active_count' => User::where('is_active', true)->count(),
                'new_count' => User::where('created_at', '>=', now()->subDays(30))->count(),
            ],
            'workflow' => [
                'submitted' => Article::where('status', Article::STATUS_SUBMITTED)->count(),
                'in_review' => Article::whereIn('status', [Article::STATUS_UNDER_REVIEW, Article::STATUS_ENDORSED])->count(),
            ],
            'activities' => $recentActivities,
            'updated_at' => now(),
        ]);
    }

    public function eicOverview(Request $request)
    {
        abort_unless($request->user()->isEIC(), 403);

        $recentActivities = Activity::with('actor:id,name,email,role,profile_picture')
            ->latest('created_at')
            ->limit(10)
            ->get()
            ->map(fn (Activity $activity) => [
                'id' => $activity->id,
                'action' => $activity->action,
                'subject' => $activity->subject_label,
                'user' => $activity->actor?->name ?? 'System',
                'role' => $activity->actor?->role ?? 'system',
                'created_at' => $activity->created_at,
            ]);

        return response()->json([
            'summary' => [
                'articles' => Article::count(),
                'endorsements' => Article::where('status', Article::STATUS_ENDORSED)->count(),
                'ready_to_publish' => Article::where('status', Article::STATUS_APPROVED)->count(),
                'published' => Article::where('status', Article::STATUS_PUBLISHED)->count(),
            ],
            'activities' => $recentActivities,
            'updated_at' => now(),
        ]);
    }

}
