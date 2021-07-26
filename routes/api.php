<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PlayerController;
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

Route::get('/teams', 'App\Http\Controllers\TeamController@index');
Route::get('/players', 'App\Http\Controllers\PlayerController@index');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
