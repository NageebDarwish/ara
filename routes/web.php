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
use App\Http\Controllers\Admin\CategoryController;
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

    // Topics routes
    Route::get('topic', [TopicController::class, 'index'])->name('topic.index');
    Route::get('topic/data', [TopicController::class, 'getTopicsData'])->name('topic.data');
    Route::get('topic/create', [TopicController::class, 'create'])->name('topic.create');
    Route::post('topic', [TopicController::class, 'store'])->name('topic.store');
    Route::get('topic/{topic}/edit', [TopicController::class, 'edit'])->name('topic.edit');
    Route::put('topic/{topic}', [TopicController::class, 'update'])->name('topic.update');
    Route::delete('topic/{topic}', [TopicController::class, 'destroy'])->name('topic.destroy');

    // Guides routes
    Route::get('guides', [GuideController::class, 'index'])->name('guides.index');
    Route::get('guides/data', [GuideController::class, 'getGuidesData'])->name('guides.data');
    Route::get('guides/create', [GuideController::class, 'create'])->name('guides.create');
    Route::post('guides', [GuideController::class, 'store'])->name('guides.store');
    Route::get('guides/{guide}/edit', [GuideController::class, 'edit'])->name('guides.edit');
    Route::put('guides/{guide}', [GuideController::class, 'update'])->name('guides.update');
    Route::delete('guides/{guide}', [GuideController::class, 'destroy'])->name('guides.destroy');

    // Levels routes
    Route::get('levels', [LevelController::class, 'index'])->name('levels.index');
    Route::get('levels/data', [LevelController::class, 'getLevelsData'])->name('levels.data');
    Route::get('levels/create', [LevelController::class, 'create'])->name('levels.create');
    Route::post('levels', [LevelController::class, 'store'])->name('levels.store');
    Route::get('levels/{level}/edit', [LevelController::class, 'edit'])->name('levels.edit');
    Route::put('levels/{level}', [LevelController::class, 'update'])->name('levels.update');
    Route::delete('levels/{level}', [LevelController::class, 'destroy'])->name('levels.destroy');

    // Permissions routes
    Route::post('/permissions/toggle', [PermissionController::class, 'toggle'])->name('permissions.toggle');

    // Country routes
    Route::get('country', [CountryController::class, 'index'])->name('country.index');
    Route::get('country/data', [CountryController::class, 'getCountriesData'])->name('country.data');
    Route::get('country/create', [CountryController::class, 'create'])->name('country.create');
    Route::post('country', [CountryController::class, 'store'])->name('country.store');
    Route::get('country/{country}/edit', [CountryController::class, 'edit'])->name('country.edit');
    Route::put('country/{country}', [CountryController::class, 'update'])->name('country.update');
    Route::delete('country/{country}', [CountryController::class, 'destroy'])->name('country.destroy');
    // Video routes
    Route::get('video', [VideoController::class, 'index'])->name('video.index');
    Route::get('video/data', [VideoController::class, 'getVideosData'])->name('video.data');
    Route::get('video/create', [VideoController::class, 'create'])->name('video.create');
    Route::post('video', [VideoController::class, 'store'])->name('video.store');
    Route::get('video/{video}/edit', [VideoController::class, 'edit'])->name('video.edit');
    Route::put('video/{video}', [VideoController::class, 'update'])->name('video.update');
    Route::delete('video/{video}', [VideoController::class, 'destroy'])->name('video.destroy');
    Route::get('fetchVideos', [VideoController::class, 'fetchChannelVideos'])->name('video.fetchVideos');
    Route::get('fetchSeries', [SeriesController::class, 'fetchChannelSeries'])->name('video.fetchSeries');

    // Series routes
    Route::get('series', [SeriesController::class, 'index'])->name('series.index');
    Route::get('series/data', [SeriesController::class, 'getSeriesData'])->name('series.data');
    Route::get('series/create', [SeriesController::class, 'create'])->name('series.create');
    Route::post('series', [SeriesController::class, 'store'])->name('series.store');
    Route::get('series/{series}/edit', [SeriesController::class, 'edit'])->name('series.edit');
    Route::put('series/{series}', [SeriesController::class, 'update'])->name('series.update');
    Route::delete('series/{series}', [SeriesController::class, 'destroy'])->name('series.destroy');
    Route::post('series/updatePlan', [SeriesController::class, 'updatePlan'])->name('series.updatePlan');
    Route::get('series/getVideosWithPlan', [SeriesController::class, 'getVideosWithPlan'])->name('series.getVideosWithPlan');

    // Page routes
    Route::get('page', [PageController::class, 'index'])->name('page.index');
    Route::get('page/data', [PageController::class, 'getPagesData'])->name('page.data');
    Route::get('page/{page}/edit', [PageController::class, 'edit'])->name('page.edit');
    Route::put('page/{page}', [PageController::class, 'update'])->name('page.update');
    Route::resource('setting', SettingController::class);

    /* manager users */
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/data', [UserController::class, 'getUsersData'])->name('users.data');
    Route::delete('users/delete/{id}', [UserController::class, 'delete'])->name('users.delete');
    Route::post('users/toggle-premium/{id}', [UserController::class, 'togglePremium'])->name('users.togglePremium');
    Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::post('users/{id}/update', [UserController::class, 'update'])->name('users.update');
    Route::get('users/{id}/password', [UserController::class, 'editPassword'])->name('users.password');
    Route::post('users/{id}/password', [UserController::class, 'updatePassword'])->name('users.password.update');

    // Blog routes
    Route::get('blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('blog/data', [BlogController::class, 'getBlogsData'])->name('blog.data');
    Route::get('blog/create', [BlogController::class, 'create'])->name('blog.create');
    Route::post('blog', [BlogController::class, 'store'])->name('blog.store');
    Route::get('blog/{blog}/edit', [BlogController::class, 'edit'])->name('blog.edit');
    Route::put('blog/{blog}', [BlogController::class, 'update'])->name('blog.update');
    Route::delete('blog/{blog}', [BlogController::class, 'destroy'])->name('blog.destroy');

    // Category routes
    Route::get('category', [CategoryController::class, 'index'])->name('category.index');
    Route::get('category/data', [CategoryController::class, 'getCategoriesData'])->name('category.data');
    Route::get('category/create', [CategoryController::class, 'create'])->name('category.create');
    Route::post('category', [CategoryController::class, 'store'])->name('category.store');
    Route::get('category/{category}/edit', [CategoryController::class, 'edit'])->name('category.edit');
    Route::put('category/{category}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('category/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');
    Route::get('contactus', [ContactUsController::class, 'index'])->name('contactus.index');
    Route::delete('contactus/{id}', [ContactUsController::class, 'destroy'])->name('contactus.destroy');
    Route::post('contactus/reply', [ContactUsController::class, 'reply'])->name('contactus.reply');
    Route::post('contactus/{id}/mark-as-read', [ContactUsController::class, 'markAsRead'])->name('contactus.markAsRead');
    // Newsletter routes
    Route::get('newsletter', [NewsLetterController::class, 'index'])->name('newsletter.index');
    Route::get('newsletter/data', [NewsLetterController::class, 'getNewslettersData'])->name('newsletter.data');
    Route::get('newsletter/search-users', [NewsLetterController::class, 'searchUsers'])->name('newsletter.searchUsers');
    Route::get('newsletter/create', [NewsLetterController::class, 'create'])->name('newsletter.create');
    Route::post('newsletter', [NewsLetterController::class, 'store'])->name('newsletter.store');
    Route::get('newsletter/{newsletter}/edit', [NewsLetterController::class, 'edit'])->name('newsletter.edit');
    Route::put('newsletter/{newsletter}', [NewsLetterController::class, 'update'])->name('newsletter.update');
    Route::delete('newsletter/{newsletter}', [NewsLetterController::class, 'destroy'])->name('newsletter.destroy');
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
