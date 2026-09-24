<?php

namespace App\Support;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Short-lived server-side cache for the public reader lists (home, categories, videos, gallery, issues).
 * Every visitor asks for the same few answers, so they are built once and shared. Saving or deleting the
 * underlying records bumps the group's version (see the models' booted() hooks), which makes the next
 * request rebuild the answer: readers see a new article straight away, not after the timer runs out.
 */
class PublicCache
{
    /** Also the longest a scheduled article can wait to go live when the scheduler isn't running. */
    private const TTL = 60;

    /**
     * @param string        $group   'articles', 'gallery' or 'issues'
     * @param string[]      $params  the query-string parameters that change the answer; anything else is
     *                               ignored, so ?junk=1 can't fill the cache with copies
     */
    public static function remember(Request $request, string $group, array $params, Closure $build): mixed
    {
        $query = $request->only($params);
        ksort($query);

        $key = sprintf('public:%s:v%d:%s:%s', $group, self::version($group), $request->path(), sha1(json_encode($query)));

        return Cache::remember($key, self::TTL, $build);
    }

    /** Throws away every cached answer in the group. */
    public static function forget(string $group): void
    {
        $key = self::versionKey($group);
        Cache::add($key, 1);
        Cache::increment($key);
    }

    private static function version(string $group): int
    {
        return (int) Cache::get(self::versionKey($group), 1);
    }

    private static function versionKey(string $group): string
    {
        return "public:{$group}:version";
    }
}
