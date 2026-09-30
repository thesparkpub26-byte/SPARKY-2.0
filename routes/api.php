<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CronController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\NewsletterController;
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
use App\Http\Controllers\ReaderSearchController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| All routes are prefixed with /api automatically by Laravel.
*/

// Public routes (no auth required)
// The reader lists below are the same for everyone, so they may be cached briefly (see PublicCache)
// Called once a minute by a free outside timer on hosts without cron (needs CRON_SECRET; see DEPLOYMENT.md)
Route::match(['get', 'post'], '/cron/run', [CronController::class, 'run'])->middleware('throttle:30,1,cron');
Route::post('/login',          [AuthController::class,  'login'])->middleware('throttle:30,1,login');
Route::post('/register/send-otp',   [RegisterController::class, 'sendOtp'])->middleware('throttle:20,1,signup-send');
Route::post('/register/verify-otp', [RegisterController::class, 'verifyOtp'])->middleware('throttle:30,1,signup-verify');
Route::post('/register/resend-otp', [RegisterController::class, 'resendOtp'])->middleware('throttle:10,1,signup-resend');

// Forgot password: emailed 6-digit code -> short-lived reset token -> new password
Route::post('/password/forgot', [PasswordResetController::class, 'forgot'])->middleware('throttle:10,1,password-forgot');
Route::post('/password/verify', [PasswordResetController::class, 'verify'])->middleware('throttle:30,1,password-verify');
Route::post('/password/reset',  [PasswordResetController::class, 'reset'])->middleware('throttle:20,1,password-reset');

Route::post('/newsletter/subscribe',   [NewsletterController::class, 'subscribe'])->middleware('throttle:10,1,newsletter-subscribe');
Route::post('/newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe'])->middleware('throttle:30,1,newsletter-unsubscribe');

Route::post('/analytics/page-view', [AnalyticsController::class, 'recordPageView'])->middleware('throttle:120,1,page-view');
Route::get('/reader/carousel',      [ArticleController::class, 'carousel'])->middleware('cache.headers:public;max_age=30;s_maxage=60;stale_while_revalidate=120;etag');
Route::get('/reader/popular',       [ArticleController::class, 'popular'])->middleware('cache.headers:public;max_age=30;s_maxage=60;stale_while_revalidate=120;etag');
// The old URL of the same list, for pages opened before the site was updated
Route::get('/reader/articles',      [ArticleController::class, 'popular'])->middleware('cache.headers:public;max_age=30;s_maxage=60;stale_while_revalidate=120;etag');
Route::get('/reader/people/{user}', [ReaderArticleController::class, 'profile'])->middleware('cache.headers:public;max_age=30;s_maxage=60;stale_while_revalidate=120;etag');
Route::get('/reader/category-articles', [ArticleController::class, 'categoryArticles'])->middleware('cache.headers:public;max_age=30;s_maxage=60;stale_while_revalidate=120;etag');
Route::get('/reader/articles/{article}',          [ReaderArticleController::class, 'show']);
Route::post('/reader/articles/{article}/read',    [ReaderArticleController::class, 'read'])->middleware('throttle:120,1,article-read');
Route::post('/reader/articles/{article}/share',   [ReaderArticleController::class, 'share'])->middleware('throttle:60,1,article-share');
Route::get('/reader/articles/{article}/comments', [ReaderArticleController::class, 'comments']);
Route::get('/reader/search',        [ReaderSearchController::class, 'search'])->middleware('throttle:60,1,search');
Route::get('/reader/videos/{article}', [ReaderArticleController::class, 'video'])->whereNumber('article')->middleware('cache.headers:public;max_age=30;s_maxage=60;stale_while_revalidate=120;etag');
Route::get('/reader/videos',        [ArticleController::class, 'videos'])->middleware('cache.headers:public;max_age=30;s_maxage=60;stale_while_revalidate=120;etag');
Route::get('/reader/issues',        [PublishedIssueController::class, 'latest'])->middleware('cache.headers:public;max_age=30;s_maxage=60;stale_while_revalidate=120;etag');
Route::get('/reader/issues/{issue}', [PublishedIssueController::class, 'publicShow']);
Route::get('/reader/gallery',       [GalleryController::class, 'latest'])->middleware('cache.headers:public;max_age=30;s_maxage=60;stale_while_revalidate=120;etag');
Route::get('/reader/gallery/{photo}', [GalleryController::class, 'publicShow']);

// Protected routes (require Sanctum token)
Route::middleware(['auth:sanctum', 'active'])->group(function () {

    // Any signed-in account (readers included)
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/reader/articles/{article}/comments', [ReaderArticleController::class, 'storeComment'])->middleware('throttle:30,1,comments');
    Route::patch('/reader/comments/{comment}', [ReaderArticleController::class, 'updateComment']);
    Route::delete('/reader/comments/{comment}', [ReaderArticleController::class, 'destroyComment']);
    Route::post('/reader/articles/{article}/like',     [ReaderArticleController::class, 'like'])->middleware('throttle:60,1,article-like');
    Route::delete('/reader/articles/{article}/like',   [ReaderArticleController::class, 'unlike'])->middleware('throttle:60,1,article-like');
    Route::post('/reader/articles/{article}/bookmark',   [ReaderArticleController::class, 'bookmark'])->middleware('throttle:60,1,article-bookmark');
    Route::delete('/reader/articles/{article}/bookmark', [ReaderArticleController::class, 'unbookmark'])->middleware('throttle:60,1,article-bookmark');
    Route::get('/reader/bookmarks', [ReaderArticleController::class, 'bookmarks']);
    Route::post('/reader/comments/{comment}/report', [ReaderArticleController::class, 'reportComment'])->middleware('throttle:10,1,comment-report');
    Route::post('/profile',         [UserController::class, 'updateProfile']);   // multipart/form-data
    Route::delete('/profile',       [UserController::class, 'deleteAccount']);

    // Publication staff only (readers are turned away)
    Route::middleware('role:staff')->group(function () {
        Route::get('/admin/overview', [AdminController::class, 'overview']);
        Route::get('/eic/overview', [AdminController::class, 'eicOverview']);
        Route::get('/section-editor/overview', [AdminController::class, 'sectionEditorOverview']);
        Route::get('/admin/analytics', [AnalyticsController::class, 'adminReport']);
        Route::get('/press-works', [PressWorkController::class, 'index']);
        Route::get('/monitoring-sheets/{monitoringSheet}', [MonitoringSheetController::class, 'show']);
        Route::put('/monitoring-sheets/{monitoringSheet}/entries/{entry}', [MonitoringSheetController::class, 'updateEntry']);
        Route::post('/monitoring-sheets/{monitoringSheet}/entries/{entry}/article', [MonitoringSheetController::class, 'uploadArticle']);
        Route::middleware('role:admin,eic,section_editor')->group(function () {
            Route::post('/press-works', [PressWorkController::class, 'store']);
            Route::delete('/press-works/{year}', [PressWorkController::class, 'destroyByYear']);
            Route::post('/monitoring-sheets/{monitoringSheet}/entries', [MonitoringSheetController::class, 'storeEntry']);
            Route::delete('/monitoring-sheets/{monitoringSheet}/entries/{entry}', [MonitoringSheetController::class, 'deleteEntry']);
        });

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

        // Users: staff can look people up (to assign work); admin / EIC create and edit accounts, only admin deletes them
        Route::get('/users',        [UserController::class, 'index']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::middleware('role:admin,eic')->group(function () {
            Route::post('/users',          [UserController::class, 'store']);
            Route::match(['put', 'patch'], '/users/{user}', [UserController::class, 'update']);

            Route::get('/newsletter/subscribers', [NewsletterController::class, 'index']);
        });

        Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware('role:admin');

        // Sections: staff read, admin / EIC manage
        Route::get('/sections',           [SectionController::class, 'index']);
        Route::get('/sections/{section}', [SectionController::class, 'show']);
        Route::middleware('role:admin,eic')->group(function () {
            Route::post('/sections',             [SectionController::class, 'store']);
            Route::match(['put', 'patch'], '/sections/{section}', [SectionController::class, 'update']);
            Route::delete('/sections/{section}', [SectionController::class, 'destroy']);
        });

        // Articles (finer rules live in ArticleController)
        Route::post('/articles/upload-media',  [ArticleController::class, 'uploadMedia']);
        Route::apiResource('articles', ArticleController::class);
        Route::post('/articles/publish-direct',    [ArticleController::class, 'publishDirect']);
        Route::post('/articles/publish-direct-video', [ArticleController::class, 'publishDirectVideo']);
        Route::put('/articles/{article}/credits',  [ArticleController::class, 'setCredits']);
        Route::post('/articles/{article}/submit',  [ArticleController::class, 'submit']);
        Route::post('/articles/{article}/endorse', [ArticleController::class, 'endorse']);
        Route::post('/articles/{article}/approve', [ArticleController::class, 'approve']);
        Route::post('/articles/{article}/reject',  [ArticleController::class, 'reject']);

        // Tasks (finer rules live in TaskController)
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
});
