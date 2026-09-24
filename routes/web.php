<?php

use App\Http\Controllers\SeoController;
use App\Http\Controllers\StoredFileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
| All routes return the main Blade template serving the Vue 3 SPA.
|
*/

Route::get('/robots.txt', [SeoController::class, 'robots']);
Route::get('/sitemap.xml', [SeoController::class, 'sitemap']);

// Article pages carry their own title / summary / picture so shared links preview properly
Route::get('/article/{id}', [SeoController::class, 'article'])->where('id', '[0-9]+');

// Uploaded photos, avatars and issue PDFs are kept in the database (see config/filesystems.php)
Route::get('/storage/{path}', [StoredFileController::class, 'show'])->where('path', '.+');

// Everything else is the single-page app, except addresses that are really data or files: an unknown
// /api/... or a missing /storage/... image must be a proper 404, not the app's page with a 200.
Route::get('/{any?}', function () {
    return view('app');
})->where('any', '^(?!(?:api|storage|build|assets|images)(?:/|$)).*');
