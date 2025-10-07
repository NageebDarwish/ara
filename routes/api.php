<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GoalController;
use App\Http\Controllers\Api\SeriesController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\DownloadListController;
use App\Http\Controllers\Api\VideoListController;
use App\Http\Controllers\Api\{SeriesListController, BadgeController};
use App\Http\Controllers\Api\{VideoHistoryController, OutsidePlatFormController};
use App\Http\Controllers\Api\{FilterController, ProgressLevelController};
use App\Http\Controllers\Api\{VideoTimelineController, VideoCountController, PageController, SettingController, PlanController, SubscriptionController, StripePaymentController, BlogController};
use App\Http\Controllers\Api\ContactUsController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\StaticsController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\SeriesVideoListController;
use App\Http\Controllers\Api\BadgeModalController;
use Illuminate\Support\Facades\Route;

Route::get('page/{name}', [PageController::class, 'index']);
Route::get('settings', [SettingController::class, 'index']);
Route::get('plans', [PlanController::class, 'index']);
Route::post('stripe', [StripePaymentController::class, 'createPaymentIntent']);
Route::post('stripe/checkout', [StripePaymentController::class, 'createCheckoutSession']);
Route::post('stripe/confirm', [StripePaymentController::class, 'confirm']);
Route::post('stripe/webhook', [StripePaymentController::class, 'webhook']);

Route::post('contact-us', [ContactUsController::class, 'store']);
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('verifyOtp', 'verifyOtp');
    Route::post('resendOtp', 'resendOtp');
    Route::post('forgotPassword', 'forgotPassword');
    Route::post('newPassword', 'newPassword');
});

Route::get('videos', [VideoController::class, 'index']);
Route::get('series', [SeriesController::class, 'index']);
Route::get('latest-posts', [PostController::class, 'latetstPosts']);

Route::controller(FilterController::class)->group(function () {
    Route::get('levels', 'levels');
    Route::get('guides', 'guides');
    Route::get('topics', 'topics');
    Route::get('suggestions', 'suggestions');
    Route::post('video_filter', 'video_filter');
    Route::post('series_filter', 'series_filter');
    Route::post('video_search', 'videoSearch');
});

Route::get('blogs', [BlogController::class, 'index']);
Route::get('get/blog/{slug}', [BlogController::class, 'show']);
Route::get('blog/categories', [BlogController::class, 'categories']);
Route::post('get/category/blogs', [BlogController::class, 'categoryBlogs']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('subscription', [SubscriptionController::class, 'store']);

    Route::get('user/detail', [AuthController::class, 'uderDetail']);
    Route::get('user/dashboard/statics', [AuthController::class, 'dashboardStatics']);

    Route::get('download/list', [DownloadListController::class, 'index']);
    Route::post('download/list', [DownloadListController::class, 'store']);

    Route::get('video/list', [VideoListController::class, 'index']);
    Route::post('video/list', [VideoListController::class, 'store']);
    Route::post('remove/video/list', [VideoListController::class, 'remove']);

    Route::get('series/video/list', [SeriesVideoListController::class, 'index']);
    Route::post('series/video/list', [SeriesVideoListController::class, 'store']);
    Route::post('remove/series/video/list', [SeriesVideoListController::class, 'remove']);

    Route::get('video/history', [VideoController::class, 'videoHistory']);

    Route::get('series/list', [SeriesListController::class, 'index']);
    Route::post('series/list', [SeriesListController::class, 'store']);
    Route::get('series/show/{id}', [SeriesController::class, 'show']);

    // Route::get('video/history',[VideoHistoryController::class,'index']);
    // Route::post('video/history',[VideoHistoryController::class,'store']);

    Route::post('timeline/video', [VideoTimelineController::class, 'store']);

    Route::get('watched/video/store', [VideoCountController::class, 'storeVideoCount']);
    Route::get('watched/video/count', [VideoCountController::class, 'videoCount']);
    Route::post('video/hide/store', [VideoCountController::class, 'is_hide']);
    Route::get('video/hide', [VideoCountController::class, 'index']);

    Route::get('hide/watched/video', [VideoController::class, 'hideWatchedVideo']);
    Route::get('hide/watched/series/video/{id}', [SeriesController::class, 'hideWatchedVideo']);

    /* is completed true */
    Route::get('video/watched/{id}', [VideoController::class, 'addToWatched']);
    Route::get('series/video/watched/{id}', [SeriesController::class, 'addToWatched']);

    Route::resource('goal', GoalController::class);
    Route::get('all/streaks', [GoalController::class, 'streaks']);
    Route::get('all/goals', [GoalController::class, 'showAll']);
    Route::post('update/goal', [GoalController::class, 'update']);

    Route::get('progress/level/list', [ProgressLevelController::class, 'index']);

    Route::post('outside/platform/store', [OutsidePlatFormController::class, 'store']);
    Route::get('outside/platform/all', [OutsidePlatFormController::class, 'index']);
    Route::post('outside/platform/update', [OutsidePlatFormController::class, 'update']);
    Route::get('outside/platform/delete/{id}', [OutsidePlatFormController::class, 'delete']);

    Route::post('/badges/assign', [BadgeController::class, 'assignBadge']);
    Route::get('user/timeline/videos', [VideoTimelineController::class, 'userTimelineVideos']);
    Route::get('user/series/timeline/videos', [VideoTimelineController::class, 'userSeriesTimelineVideos']);

    Route::post('edit/profile', [AuthController::class, 'editProfile']);
    Route::post('change/password', [AuthController::class, 'changePassword']);


    Route::resource('post', PostController::class);
    Route::post('post/comment/{post_id}', [PostController::class, 'addComment']);
    Route::get('post/add/like/{post_id}', [PostController::class, 'addLike']);
    Route::get('post/add/dislike/{post_id}', [PostController::class, 'addDisLike']);
    Route::get('get/post/tags', [PostController::class, 'getTags']);
    Route::get('get/all/posts', [PostController::class, 'getAll']);
    Route::get('get/all/myanswers', [PostController::class, 'myAnswers']);
    Route::get('get/all/saved-posts', [PostController::class, 'savedPosts']);
    Route::post('post/save-unsave/{post_id}', [PostController::class, 'saveUnsavePost']);
    Route::get('get/posts/tag/{id}', [PostController::class, 'getPostsByTag']);
    Route::get('like-comment/{comment_id}', [PostController::class, 'likeComment']);

    Route::get('statics', [StaticsController::class, 'index']);
    Route::post('add/video/comment', [CommentController::class, 'store']);
    Route::get('like/video/comment/{comment_id}', [CommentController::class, 'likeComment']);

    Route::get('badge/modal/{id}', [BadgeModalController::class, 'store']);
});
