<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\PageView;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function recordPageView(Request $request)
    {
        $validated = $request->validate([
            'page_path' => 'required|string|max:500',
            'page_title' => 'nullable|string|max:255',
            'article_id' => 'nullable|integer|exists:articles,id',
        ]);

        PageView::create([
            ...$validated,
            'visitor_hash' => PageView::visitorHash($request),
        ]);

        return response()->json(['message' => 'Page view recorded.'], 201);
    }

    public function adminReport(Request $request)
    {
        abort_unless($request->user()->isAdmin() || $request->user()->isEIC(), 403);

        $period = $request->integer('period', 30);
        $days = in_array($period, [7, 30, 90], true) ? $period : 30;
        $since = now()->subDays($days - 1)->startOfDay();
        $views = PageView::where('created_at', '>=', $since);

        $topPages = (clone $views)
            ->selectRaw('page_title as title, COUNT(*) as views, COUNT(DISTINCT visitor_hash) as users')
            ->whereNotNull('page_title')
            ->whereNotNull('article_id')
            ->groupBy('page_title')
            ->orderByDesc('views')
            ->limit(5)
            ->get()
            ->map(fn (PageView $page) => [
                'title' => $page->title,
                'views' => (int) $page->views,
                'users' => (int) $page->users,
            ])
            ->values();

        // When during the day articles are opened: views and distinct visitors for each hour (app timezone)
        $hourlyViews = array_fill(0, 24, 0);
        $hourlyVisitors = array_fill(0, 24, []);
        (clone $views)->whereNotNull('article_id')->get(['created_at', 'visitor_hash'])->each(function (PageView $view) use (&$hourlyViews, &$hourlyVisitors) {
            $hour = $view->created_at->hour;
            $hourlyViews[$hour]++;
            $hourlyVisitors[$hour][$view->visitor_hash] = true;
        });

        $published = Article::where('status', Article::STATUS_PUBLISHED)->count();

        return response()->json([
            'configured' => true,
            'source' => 'application',
            'period' => $days,
            'start_date' => $since->toDateString(),
            'end_date' => now()->toDateString(),
            'metrics' => [
                'page_views' => (clone $views)->count(),
                'active_users' => (clone $views)->distinct('visitor_hash')->count('visitor_hash'),
                'sessions' => (clone $views)->distinct('visitor_hash')->count('visitor_hash'),
                'average_session_duration' => 0,
            ],
            'top_pages' => $topPages,
            'hourly' => [
                'views'    => $hourlyViews,
                'visitors' => array_map('count', $hourlyVisitors),
            ],
            'content' => [
                'published_articles' => $published,
                'total_articles' => Article::count(),
            ],
        ]);
    }
}
