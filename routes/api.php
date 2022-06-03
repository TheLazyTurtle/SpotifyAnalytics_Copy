<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PassportAuthController;
use App\Http\Controllers\PlayedController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\ArtistController;
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

Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login']);
Route::resource('song', SongController::class);
Route::get('artist/albums', [ArtistController::class, 'albums']);
// Route::resource('artist', ArtistController::class);
Route::get('user/guest/{id}', [UserController::class, 'showGuest']);

Route::middleware('auth:api')->group(function () {
    Route::get('/played/allSongsPlayed', [PlayedController::class, 'allSongsPlayed']);
    Route::get('/played/topSongs', [PlayedController::class, 'topSongs']);
    Route::get('/played/topArtists', [PlayedController::class, 'topArtists']);
    Route::get('/played/playedPerDay', [PlayedController::class, 'playedPerDay']);
    Route::get('/played/topArtistSearch', [PlayedController::class, 'topArtistSearch']);
    Route::get('/played/topSongsSearch', [PlayedController::class, 'topSongsSearch']);
    Route::get('/played/timeListend', [PlayedController::class, 'timeListend']);
    Route::get('/played/amountSongs', [PlayedController::class, 'amountSongs']);
    Route::get('/played/amountNewSongs', [PlayedController::class, 'amountNewSongs']);

    Route::get('artist/topSongs', [ArtistController::class, 'topSongs']);
    Route::get('artist/{id}', [ArtistController::class, 'show']);

    Route::get('user/', [UserController::class, 'getCurrentUser']);
    Route::get('user/{id}', [UserController::class, 'show']);
    Route::post('user/follow', [UserController::class, 'follow']);
});
