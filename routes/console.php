<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Needs the scheduler running: `php artisan schedule:work` locally, or a cron entry running
// `php artisan schedule:run` every minute on the server.
Schedule::command('articles:publish-scheduled')->everyMinute()->withoutOverlapping();
Schedule::command('newsletter:digest')->weeklyOn(1, '08:00')->withoutOverlapping();
Schedule::command('sanctum:prune-expired --hours=24')->daily();
Schedule::command('maintenance:prune')->dailyAt('03:00')->withoutOverlapping();
