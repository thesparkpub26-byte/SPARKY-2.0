<?php

namespace App\Support;

use App\Models\Article;
use App\Models\PageView;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * The "Popular now" ranking: the most-read articles of the current week.
 *
 * Weeks run Sunday to Saturday (Philippine time) and the ranking starts fresh each Sunday, but only once a
 * new article has been published that week. Until then, the list of the last week that had a new article
 * stays up, so the section never resets to nothing on a quiet week.
 *
 * "Read" counts each visitor once per article (a reader refreshing the page doesn't climb the list);
 * total opens only break ties.
 */
class PopularArticles
{
    private const TIMEZONE = 'Asia/Manila';

    /**
     * The week the ranking is taken from, as [start, end) in the app's timezone: the week of the most
     * recently published article, which is the current week as soon as anything is published in it.
     *
     * @return array{0: Carbon, 1: Carbon}|null null when nothing has been published yet
     */
    public static function window(?Carbon $now = null): ?array
    {
        $now ??= Carbon::now();
        $newest = self::published()->where('published_at', '<=', $now)->max('published_at');

        if (!$newest) {
            return null;
        }

        $start = Carbon::parse($newest)->setTimezone(self::TIMEZONE)->startOfWeek(Carbon::SUNDAY);

        return [$start->copy()->setTimezone(config('app.timezone')), $start->copy()->addWeek()->setTimezone(config('app.timezone'))];
    }

    /** The ids of the $limit most popular articles, most popular first. */
    public static function ids(int $limit): array
    {
        $window = self::window();
        if (!$window) {
            return [];
        }

        [$from, $to] = $window;

        $ranked = PageView::query()
            ->whereNotNull('article_id')
            ->where('created_at', '>=', $from)
            ->where('created_at', '<', $to)
            ->whereIn('article_id', self::published()->select('id'))
            ->select('article_id', DB::raw('COUNT(DISTINCT visitor_hash) as readers'), DB::raw('COUNT(*) as opens'))
            ->groupBy('article_id')
            ->orderByDesc('readers')
            ->orderByDesc('opens')
            ->limit($limit)
            ->pluck('article_id')
            ->all();

        if (count($ranked) >= $limit) {
            return $ranked;
        }

        // Not enough readers yet to fill the list: top up with what was published that week, then the newest overall
        $fill = self::published()
            ->whereNotIn('id', $ranked)
            ->orderByRaw('CASE WHEN published_at >= ? AND published_at < ? THEN 0 ELSE 1 END', [$from, $to])
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit($limit - count($ranked))
            ->pluck('id')
            ->all();

        return array_merge($ranked, $fill);
    }

    /** Base query: published, non-video articles that have a publish date. */
    private static function published()
    {
        return Article::where('status', Article::STATUS_PUBLISHED)
            ->where('type', '!=', Article::TYPE_VIDEO)
            ->whereNotNull('published_at');
    }
}
