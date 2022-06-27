<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayedController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;

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

// Artist
Route::get('artist/albums', [ArtistController::class, 'albums']);
Route::get('user/{id}', [UserController::class, 'show']);

// Graphs
Route::get('played/allSongsPlayed', [PlayedController::class, 'allSongsPlayed']);
Route::get('played/topSongs', [PlayedController::class, 'topSongs']);
Route::get('played/topArtists', [PlayedController::class, 'topArtists']);
Route::get('played/playedPerDay', [PlayedController::class, 'playedPerDay']);

// Search
Route::get('search', [PlayedController::class, 'search']);
Route::get('played/topArtistSearch', [PlayedController::class, 'topArtistSearch']);
Route::get('played/topSongsSearch', [PlayedController::class, 'topSongsSearch']);

// Artists
Route::get('artist/topSongs', [ArtistController::class, 'topSongs']);
Route::get('artist/{id}', [ArtistController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    // Slider
    Route::get('played/timeListened', [PlayedController::class, 'timeListened']);
    Route::get('played/amountSongs', [PlayedController::class, 'amountSongs']);
    Route::get('played/amountNewSongs', [PlayedController::class, 'amountNewSongs']);

    // Current user
    Route::get('user/', [UserController::class, 'getCurrentUser']);
    Route::post('user/follow', [UserController::class, 'follow']);

    // Notifications
    Route::post('notification/create', [NotificationController::class, 'store']);
    Route::post('notification/delete', [NotificationController::class, 'destroy']);
    Route::post('notification/handle', [NotificationController::class, 'handle']);
    Route::get('notification/', [NotificationController::class, 'index']);
});
