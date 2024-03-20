<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\GeocodeController;
use App\Http\Controllers\SpotSearchController;
use App\Http\Controllers\SpotController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/favorite/add/{spot}', [SpotController::class, 'addFavorite']);
    Route::delete('/favorite/delete/{spot}', [SpotController::class, 'removeFavorite']);
    Route::get('/favorite/{spot}', [SpotController::class, 'getFavoriteStatus']);
});


Route::get('/geocode', [GeocodeController::class, 'geocode']);

Route::get('/search', [SpotSearchController::class, 'search']);

