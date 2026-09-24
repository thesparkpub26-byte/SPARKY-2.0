<?php

return [

    'default' => env('FILESYSTEM_DISK', 'local'),

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => false,   // Laravel's built-in /storage/{path} route would clash with the one below
            'throw' => false,
            'report' => false,
        ],

        // Uploaded photos, avatars and issue PDFs. They are kept in the database (see the "database" driver in
        // App\Providers\AppServiceProvider), not on the server's disk, because a host such as Render wipes
        // its disk on every deploy. Files are served from /storage/{path} by StoredFileController.
        'public' => [
            'driver' => 'database',
            'url' => env('APP_URL', 'http://localhost') . '/storage',
            'throw' => false,
            'report' => false,
        ],

    ],

    // The old `php artisan storage:link` is no longer needed: /storage/* is a real route now.
    'links' => [],

];
