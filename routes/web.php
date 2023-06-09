<?php

use App\Http\Controllers\SystemController;
use Illuminate\Support\Facades\Route;

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
    return view('index');
});

Route::get('/addSpotifyTokens', function () {
    return view('addSpotifyTokens');
});

Route::get('/system/syncUsers/{id}', [SystemController::class, 'fetch']);

Route::view('/{path?}', 'index')
    ->where('path', '.*')
    ->name('react');
