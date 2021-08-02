<?php
   
use Illuminate\Support\Facades\Route;
   
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\ApiController;
   
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
   

Auth::routes();
   
Route::get('/', 'App\Http\Controllers\ApiController@getTeams');
Route::get('/home', 'App\Http\Controllers\ApiController@getTeams');
   
Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);	
});
//Create get,post,put,delete end points for REST API
Route::resource('teams', TeamController::class);
Route::resource('players', PlayerController::class);

//path to call internal apis and display the same in the UI
Route::get('/apiTeamList', 'App\Http\Controllers\ApiController@getTeams');
Route::get('/apiTeamDetailedList/{id}', 'App\Http\Controllers\ApiController@getTeamDetails');
Route::get('/apiTeamCreate', 'App\Http\Controllers\ApiController@createTeam');
Route::post('/apiTeamStore', 'App\Http\Controllers\ApiController@storeTeam');
Route::get('/apiTeamEdit/{id}', 'App\Http\Controllers\ApiController@editTeam');
Route::put('/apiTeamUpdate', 'App\Http\Controllers\ApiController@updateTeam');
Route::delete('/apiTeamDelete/{id}', 'App\Http\Controllers\ApiController@destroyTeam');

Route::get('/apiPlayerList', 'App\Http\Controllers\ApiController@getPlayers');
Route::get('/apiPlayerDetailedList/{id}', 'App\Http\Controllers\ApiController@getPlayerDetails');
Route::get('/apiPlayerCreate', 'App\Http\Controllers\ApiController@createPlayer');
Route::post('/apiPlayerStore', 'App\Http\Controllers\ApiController@storePlayer');
Route::get('/apiPlayerEdit/{id}', 'App\Http\Controllers\ApiController@editPlayer');
Route::put('/apiPlayerUpdate', 'App\Http\Controllers\ApiController@updatePlayer');
Route::delete('/apiPlayerDelete/{id}', 'App\Http\Controllers\ApiController@destroyPlayer');
