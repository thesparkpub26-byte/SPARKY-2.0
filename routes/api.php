<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PressWorkController;
use App\Http\Controllers\MonitoringSheetController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| All routes are prefixed with /api automatically by Laravel.
*/

// Public routes (no auth required)
Route::post('/login',          [AuthController::class,  'login']);
Route::post('/register/send-otp',   [RegisterController::class, 'sendOtp']);
Route::post('/register/verify-otp', [RegisterController::class, 'verifyOtp']);
Route::post('/register/resend-otp', [RegisterController::class, 'resendOtp']);

// Protected routes (require Sanctum token)
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/admin/overview', [AdminController::class, 'overview']);
    Route::get('/press-works', [PressWorkController::class, 'index']);
    Route::post('/press-works', [PressWorkController::class, 'store']);
    Route::delete('/press-works/{year}', [PressWorkController::class, 'destroyByYear']);
    Route::get('/monitoring-sheets/{monitoringSheet}', [MonitoringSheetController::class, 'show']);
    Route::post('/monitoring-sheets/{monitoringSheet}/entries', [MonitoringSheetController::class, 'storeEntry']);
    Route::put('/monitoring-sheets/{monitoringSheet}/entries/{entry}', [MonitoringSheetController::class, 'updateEntry']);
    Route::delete('/monitoring-sheets/{monitoringSheet}/entries/{entry}', [MonitoringSheetController::class, 'deleteEntry']);
    Route::post('/monitoring-sheets/{monitoringSheet}/entries/{entry}/article', [MonitoringSheetController::class, 'uploadArticle']);

    // Self-service profile
    Route::post('/profile',         [UserController::class, 'updateProfile']);   // multipart/form-data
    Route::delete('/profile',       [UserController::class, 'deleteAccount']);

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
