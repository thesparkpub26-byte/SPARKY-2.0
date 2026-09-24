<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Needs something to run `php artisan schedule:run` every minute: `php artisan schedule:work` locally, a cron entry
// on a server, or the outside timer that calls /api/cron/run on a host without cron (see DEPLOYMENT.md).
//
// The jobs run inside the same process (Schedule::call) instead of Schedule::command(), which would start a
// new `php artisan` process for each job: slower, and inside a web request it would need its own copy of the
// PHP program and settings.
$job = fn (string $command) => Schedule::call(fn () => Artisan::call($command))->name($command);

$job('articles:publish-scheduled')->everyMinute()->withoutOverlapping();
$job('newsletter:digest')->weeklyOn(1, '08:00')->withoutOverlapping();
$job('sanctum:prune-expired --hours=24')->daily();
$job('maintenance:prune')->dailyAt('03:00')->withoutOverlapping();
