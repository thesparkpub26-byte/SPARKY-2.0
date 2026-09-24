<?php

/*
| The site and its API are served from the same address, so a browser never needs cross-origin access.
| Only the site's own URL (plus anything listed in CORS_ALLOWED_ORIGINS) may call the API from another origin.
*/
return [
    'paths' => ['api/*'],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    'allowed_origins' => array_values(array_filter(array_merge(
        [rtrim((string) env('APP_URL', ''), '/')],
        array_map('trim', explode(',', (string) env('CORS_ALLOWED_ORIGINS', ''))),
        // the Vite dev server, only while developing
        env('APP_ENV') === 'local' ? ['http://localhost:5173', 'http://127.0.0.1:5173'] : [],
    ))),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['Authorization', 'Content-Type', 'Accept', 'X-Requested-With'],

    'exposed_headers' => [],

    'max_age' => 600,

    'supports_credentials' => false,
];
