<?php

return [
    /*
    | Content-Security-Policy for the site's pages (see App\Http\Middleware\SecurityHeaders):
    |   enforce - the browser blocks anything the policy doesn't allow (default on a live site)
    |   report  - the browser only lists violations in its console (default while developing)
    |   off     - no policy
    */
    'csp_mode' => env('CSP_MODE', env('APP_ENV') === 'production' ? 'enforce' : 'report'),

    /*
    | Secret for /api/cron/run, the address an outside timer calls every minute to run the scheduled jobs on a host
    | without cron (see App\Http\Controllers\CronController). At least 24 characters; empty switches the address off.
    */
    'cron_secret' => env('CRON_SECRET'),

    /*
    | Sign-up email verification (the 6-digit code). Switched OFF for now because Render's free plan cannot reach an
    | SMTP server, so no code could be delivered: new accounts are created straight away, without proving the
    | email address. Set SIGNUP_OTP_ENABLED=true (or change the default) once email can be sent again.
    */
    'signup_otp' => filter_var(env('SIGNUP_OTP_ENABLED', false), FILTER_VALIDATE_BOOLEAN),
];
