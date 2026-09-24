<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Debug pages print environment values (including passwords) and file paths. If a live server is
        // ever left with APP_DEBUG=true, switch it off rather than expose them.
        if ($this->app->environment('production') && config('app.debug')) {
            config(['app.debug' => false]);
        }

        // A live site served over https builds every link (emails, sitemap, previews) with https
        if ($this->app->environment('production') && str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        // Overall ceiling for the API: signed-in people get their own allowance, anonymous visitors share
        // their address's (generous, because a whole campus can sit behind one)
        RateLimiter::for('api', fn (Request $request) => $request->user()
            ? Limit::perMinute(300)->by('user:' . $request->user()->id)
            : Limit::perMinute(1200)->by('ip:' . $request->ip()));
    }
}
