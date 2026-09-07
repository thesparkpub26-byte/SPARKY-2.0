<?php

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

Route::get('/{any?}', function () {
    return view('app');
})->where('any', '.*');
