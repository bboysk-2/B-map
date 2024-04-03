<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\SpotController;
use App\Http\Controllers\ReviewController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::get('/policy', function () {
    return Inertia::render('Policy');
})->name('policy');

Route::get('/rules', function () {
    return Inertia::render('Rules');
})->name('rules');

Route::get('/contact', function () {
    return Inertia::render('Contact');
})->name('contact');


// -------------------- マイページ --------------------
Route::get('/mypage', [MyPageController::class, 'index'])
->middleware(['auth', 'verified'])
->name('mypage');


// -------------------- ソーシャルログイン --------------------
Route::get('/auth/{provider}/callback', [SocialLoginController::class, 'providerCallback']);
Route::get('social-auth/{provider}', [SocialLoginController::class, 'redirectToProvider']);


// -------------------- スポット関連 --------------------
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/spots/create', [SpotController::class, 'create'])
    ->name('spots.create');

    Route::post('/spots/store', [SpotController::class, 'store'])
    ->name('spots.store');

    Route::get('/spots/{spot}/edit', [SpotController::class, 'edit'])
    ->name('spots.edit');

    Route::patch('/spots/{spot}', [SpotController::class, 'update'])
    ->name('spots.update');

    Route::delete('/spots/{spot}', [SpotController::class, 'destroy'])
    ->name('spots.destroy');
});

Route::get('/spots/index', [SpotController::class, 'index'])
->name('spots.index');

Route::get('/spots/{spot}', [SpotController::class, 'show'])
->name('spots.show');


// -------------------- レビュー関連 --------------------
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/reviews/create', [ReviewController::class, 'create'])
    ->name('reviews.create');

    Route::post('/reviews/store', [ReviewController::class, 'store'])
    ->name('reviews.store');

    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])
    ->name('reviews.edit');
});


// -------------------- プロフィール --------------------
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
    ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
    ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
    ->name('profile.destroy');

    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])
    ->name('avatar.update');
});

require __DIR__.'/auth.php';
