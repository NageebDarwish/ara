<?php

use App\Http\Controllers\Admin\{CountryController, UserController};
use App\Http\Controllers\Admin\SeriesController;
use App\Http\Controllers\Admin\TopicController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\GuideController;
use App\Http\Controllers\Admin\VideoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ContactUsController;
use App\Http\Controllers\Admin\NewsLetterController;
use App\Http\Controllers\Admin\ManagerController;
use App\Http\Controllers\Admin\PermissionController;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::prefix('admin')->middleware(['auth', 'role:admin,manager'])->as('admin.')->group(function () {

    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::resource('manager', ManagerController::class);
    Route::resource('topic', TopicController::class);
    Route::resource('guides', GuideController::class);
    Route::resource('levels', LevelController::class);
    
    // Permissions routes
    Route::post('/permissions/toggle', [PermissionController::class, 'toggle'])->name('permissions.toggle');
    Route::resource('country', CountryController::class);
    Route::resource('video', VideoController::class);
    Route::get('fetchVideos', [VideoController::class, 'fetchChannelVideos'])->name('video.fetchVideos');
    Route::get('fetchSeries', [SeriesController::class, 'fetchChannelSeries'])->name('video.fetchSeries');
    Route::resource('series', SeriesController::class);
    Route::post('series/updatePlan', [SeriesController::class, 'updatePlan'])->name('series.updatePlan');
    Route::get('series/getVideosWithPlan', [SeriesController::class, 'getVideosWithPlan'])->name('series.getVideosWithPlan');
    Route::resource('page', PageController::class);
    Route::resource('setting', SettingController::class);

    /* manager users */
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::delete('users/delete/{id}', [UserController::class, 'delete'])->name('users.delete');

    Route::resource('blog', BlogController::class);
    Route::get('contactus', [ContactUsController::class, 'index'])->name('contactus.index');
    Route::delete('contactus/{id}', [ContactUsController::class, 'destroy'])->name('contactus.destroy');
    Route::post('contactus/reply', [ContactUsController::class, 'reply'])->name('contactus.reply');
    Route::post('contactus/{id}/mark-as-read', [ContactUsController::class, 'markAsRead'])->name('contactus.markAsRead');
    Route::get('newsletter', [NewsLetterController::class, 'index'])->name('newsletter.index');
    Route::post('send/newsletter', [NewsLetterController::class, 'sendNewsLetter'])->name('newsletter.send');
});

Route::get('/admin/video/auth/google', [VideoController::class, 'googleAuth'])->name('admin.video.auth.google');
Route::get('/admin/video/auth/google/callback', [VideoController::class, 'googleCallback'])->name('admin.video.auth.google.callback');

// Route::get('/migration', function(){
//     // Run a specific migration file
//     Artisan::call('migrate', [
//         '--path' => 'database/migrations/2025_07_30_061147_create_badge_modals_table.php'
//     ]);
//     return "Migration for specific table completed";
// });


// Route::get('/seed', function(){

//         Artisan::call('db:seed');
//     return "seed  completed";
// });
