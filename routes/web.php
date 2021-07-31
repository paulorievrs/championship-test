<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamsController;
use App\Http\Controllers\MatchesController;
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
    return view('home');
});

Route::group(['prefix' => 'teams'], function () {
    Route::get('/select', [ TeamsController::class, 'getTeams' ]);
    Route::get('/', [ TeamsController::class, 'getTeamsTable' ]);
});

Route::group(['prefix' => 'matches'], function () {
    Route::post('/create', [ MatchesController::class, 'createMatch' ]);
});

