<?php

use App\Http\Controllers\PusherController;
use App\Http\Controllers\StreamingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Preprocessing\PreprocessingService;
use Illuminate\Support\Facades\Auth;

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
    return view('welcome');
});

Route::get('/test', [TestController::class, 'index']);
Route::get('/stream', [StreamingController::class,'index']);

Route::get('/cek', function () {
    $text = 'Bosan dan jenuh dirumah menunggu vaksin covid';
    echo PreprocessingService::index($text);
});

Route::get('/pusher',[PusherController::class,'index']);

Auth::routes();

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
	Route::get('{page}', ['as' => 'page.index', 'uses' => 'App\Http\Controllers\PageController@index']);
});