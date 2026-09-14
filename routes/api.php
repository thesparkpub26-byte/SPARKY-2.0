<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SectionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| All routes are prefixed with /api automatically by Laravel.
*/

// Public routes (no auth required)
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require Sanctum token)
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Users (Admin only actions wrapped in frontend)
    Route::apiResource('users', UserController::class);

    // Sections
    Route::apiResource('sections', SectionController::class);

    // Articles
    Route::apiResource('articles', ArticleController::class);
    Route::post('/articles/{article}/submit',  [ArticleController::class, 'submit']);
    Route::post('/articles/{article}/endorse', [ArticleController::class, 'endorse']);
    Route::post('/articles/{article}/approve', [ArticleController::class, 'approve']);
    Route::post('/articles/{article}/reject',  [ArticleController::class, 'reject']);

    // Tasks
    Route::apiResource('tasks', TaskController::class);
    Route::post('/tasks/{task}/submit',   [TaskController::class, 'submit']);
    Route::post('/tasks/{task}/return',   [TaskController::class, 'return']);
    Route::post('/tasks/{task}/complete', [TaskController::class, 'complete']);

    // Notifications
    Route::get('/notifications',                                [NotificationController::class, 'index']);
    Route::post('/notifications/{notification}/read',           [NotificationController::class, 'markRead']);
    Route::post('/notifications/read-all',                      [NotificationController::class, 'markAllRead']);
    Route::delete('/notifications/{notification}',              [NotificationController::class, 'destroy']);
});
