<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\SpotController;

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


Route::get('/mypage', [MyPageController::class, 'index'])
->middleware(['auth', 'verified'])
->name('mypage');


Route::get('/auth/{provider}/callback', [SocialLoginController::class, 'providerCallback']);

Route::get('social-auth/{provider}', [SocialLoginController::class, 'redirectToProvider']);


Route::get('/spots/index', [SpotController::class, 'index'])
->name('spots.index');

Route::get('/spots/create', [SpotController::class, 'create'])
->middleware(['auth', 'verified'])
->name('spots.create');

Route::post('/spots/store', [SpotController::class, 'store'])
->middleware(['auth', 'verified'])
->name('spots.store');


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
