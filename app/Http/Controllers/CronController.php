<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

/**
 * Runs whatever is due in routes/console.php (scheduled articles, the weekly newsletter, clean-up), for hosts
 * that have no cron. A free outside timer (see DEPLOYMENT.md) calls this address once a minute with the
 * secret in an "Authorization: Bearer ..." header, exactly like `php artisan schedule:run` in a cron entry.
 */
class CronController extends Controller
{
    /** A short secret could be guessed; anything shorter than this counts as "not set up". */
    private const MIN_SECRET_LENGTH = 24;

    /** Runs the scheduled jobs that are due; called every minute by the outside timer, and only with the secret key. */
    public function run(Request $request)
    {
        $secret = (string) config('security.cron_secret');

        // Switched off unless CRON_SECRET is set: the address does not even exist for anyone else
        abort_if(strlen($secret) < self::MIN_SECRET_LENGTH, 404);

        if (!hash_equals($secret, (string) $request->bearerToken())) {
            Log::warning('The cron address was called with a wrong key', ['ip' => $request->ip()]);
            abort(403);
        }

        // Free timers give up after about 30 seconds. A newsletter to a long list takes longer, and it must
        // still be finished (and not sent twice: the job guards against overlapping runs).
        ignore_user_abort(true);
        set_time_limit(300);

        Artisan::call('schedule:run');

        return response()->json(['ok' => true, 'ran_at' => now()->toIso8601String()]);
    }
}
