<?php

return [
    /*
    | Content-Security-Policy for the site's pages (see App\Http\Middleware\SecurityHeaders):
    |   enforce - the browser blocks anything the policy doesn't allow (default on a live site)
    |   report  - the browser only lists violations in its console (default while developing)
    |   off     - no policy
    */
    'csp_mode' => env('CSP_MODE', env('APP_ENV') === 'production' ? 'enforce' : 'report'),
];
