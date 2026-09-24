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
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\PublishedIssueController;
use App\Http\Controllers\ReaderArticleController;

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
Route::post('/analytics/page-view', [AnalyticsController::class, 'recordPageView']);
Route::get('/reader/carousel',      [ArticleController::class, 'carousel']);
Route::get('/reader/articles',      [ArticleController::class, 'latest']);
Route::get('/reader/category-articles', [ArticleController::class, 'categoryArticles']);
Route::get('/reader/articles/{article}',          [ReaderArticleController::class, 'show']);
Route::post('/reader/articles/{article}/read',    [ReaderArticleController::class, 'read']);
Route::post('/reader/articles/{article}/share',   [ReaderArticleController::class, 'share']);
Route::get('/reader/articles/{article}/comments', [ReaderArticleController::class, 'comments']);
Route::get('/reader/videos',        [ArticleController::class, 'videos']);
Route::get('/reader/issues',        [PublishedIssueController::class, 'latest']);
Route::get('/reader/issues/{issue}', [PublishedIssueController::class, 'publicShow']);
Route::get('/reader/gallery',       [GalleryController::class, 'latest']);

// Protected routes (require Sanctum token)
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/admin/overview', [AdminController::class, 'overview']);
    Route::get('/eic/overview', [AdminController::class, 'eicOverview']);
    Route::get('/section-editor/overview', [AdminController::class, 'sectionEditorOverview']);
    Route::get('/admin/analytics', [AnalyticsController::class, 'adminReport']);
    Route::get('/press-works', [PressWorkController::class, 'index']);
    Route::post('/press-works', [PressWorkController::class, 'store']);
    Route::delete('/press-works/{year}', [PressWorkController::class, 'destroyByYear']);
    Route::get('/monitoring-sheets/{monitoringSheet}', [MonitoringSheetController::class, 'show']);
    Route::post('/monitoring-sheets/{monitoringSheet}/entries', [MonitoringSheetController::class, 'storeEntry']);
    Route::put('/monitoring-sheets/{monitoringSheet}/entries/{entry}', [MonitoringSheetController::class, 'updateEntry']);
    Route::delete('/monitoring-sheets/{monitoringSheet}/entries/{entry}', [MonitoringSheetController::class, 'deleteEntry']);
    Route::post('/monitoring-sheets/{monitoringSheet}/entries/{entry}/article', [MonitoringSheetController::class, 'uploadArticle']);

    // Gallery
    Route::get('/gallery',            [GalleryController::class, 'index']);
    Route::get('/gallery/artists',    [GalleryController::class, 'artists']);
    Route::post('/gallery',           [GalleryController::class, 'store']);
    Route::post('/gallery/{photo}',   [GalleryController::class, 'update']);   // multipart/form-data
    Route::delete('/gallery/{photo}', [GalleryController::class, 'destroy']);

    // Published Issues
    Route::get('/published-issues',            [PublishedIssueController::class, 'index']);
    Route::get('/published-issues/{issue}',    [PublishedIssueController::class, 'show']);
    Route::post('/published-issues',           [PublishedIssueController::class, 'store']);
    Route::post('/published-issues/{issue}',   [PublishedIssueController::class, 'update']);  // multipart/form-data
    Route::delete('/published-issues/{issue}', [PublishedIssueController::class, 'destroy']);

    // Reader comments (signed-in users only)
    Route::post('/reader/articles/{article}/comments', [ReaderArticleController::class, 'storeComment'])->middleware('throttle:30,1');
    Route::patch('/reader/comments/{comment}', [ReaderArticleController::class, 'updateComment']);
    Route::delete('/reader/comments/{comment}', [ReaderArticleController::class, 'destroyComment']);

    // Self-service profile
    Route::post('/profile',         [UserController::class, 'updateProfile']);   // multipart/form-data
    Route::delete('/profile',       [UserController::class, 'deleteAccount']);

    // Users (Admin only actions wrapped in frontend)
    Route::apiResource('users', UserController::class);

    // Sections
    Route::apiResource('sections', SectionController::class);

    // Articles
    Route::post('/articles/upload-media',  [ArticleController::class, 'uploadMedia']);
    Route::apiResource('articles', ArticleController::class);
    Route::post('/articles/publish-direct',    [ArticleController::class, 'publishDirect']);
    Route::post('/articles/publish-direct-video', [ArticleController::class, 'publishDirectVideo']);
    Route::put('/articles/{article}/credits',  [ArticleController::class, 'setCredits']);
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
    Route::match(['post', 'patch'], '/notifications/{notification}/read', [NotificationController::class, 'markRead']);
    Route::post('/notifications/read-all',                      [NotificationController::class, 'markAllRead']);
    Route::delete('/notifications',                             [NotificationController::class, 'clearAll']);
    Route::delete('/notifications/{notification}',              [NotificationController::class, 'destroy']);
});
