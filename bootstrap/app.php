<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role'   => \App\Http\Middleware\EnsureRole::class,
            'active' => \App\Http\Middleware\EnsureActiveAccount::class,
        ]);

        // On a host such as Render the app sits behind the host's load balancer. Without this every visitor
        // looks like the balancer's address, so the per-visitor request limits, sign-in lockouts and unique
        // visitor counts would all be shared by everyone, and https links would be built as http.
        $middleware->trustProxies(at: '*');

        // Laravel's default sends signed-out visitors to route('login'), which does not exist here (the login
        // screen belongs to the Vue app), so it crashed with a 500. The API never redirects; it answers 401.
        $middleware->redirectGuestsTo(fn ($request) => $request->is('api/*') ? null : '/login');

        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);

        // Every request to /api is counted (limits are set in AppServiceProvider)
        $middleware->throttleApi('api');

        // Sign-in uses tokens, not cookies, so pages need no session or CSRF cookie: nothing for an attacker to ride
        $middleware->web(remove: [
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // The API always answers in JSON (never an HTML error page)
        $exceptions->shouldRenderJsonWhen(fn ($request, \Throwable $e) => $request->is('api/*') || $request->expectsJson());

        // Not signed in: a plain 401, whatever the caller asked for. Without this, a request that does not send
        // "Accept: application/json" (a script, a crawler, a browser address bar) made Laravel look for a login
        // page that does not exist and fail with a 500.
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
        });
    })->create();
