<?php

use App\Http\Controllers\PusherController;
use App\Http\Controllers\StreamingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TextMiningController;
use App\Http\Controllers\UploadTrainingController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Rubix\ML\Classifiers\GaussianNB;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\PersistentModel;
use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\Pipeline;
use Rubix\ML\Transformers\TfIdfTransformer;
use Rubix\ML\Transformers\WordCountVectorizer;

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
Route::get('/stream', [StreamingController::class, 'index']);

Route::get('/cek', function () {		
	$samples = [["pingin keluar rumah takut covid"], ["khawatir bakal gelombang covid lanjut"], ["alhamdulillah banyak sembuh covid"], ["syukur banget bapak ibu negatif covid"]];

	$labels = ["takut", "takut", "senang", "senang"];

	$dataset = Labeled::build($samples, $labels);
	$folds = $dataset->fold(4);

	$estimator = new PersistentModel(
		new Pipeline([
			new WordCountVectorizer(),
			new TfIdfTransformer(),
		], new GaussianNB()),
		new Filesystem(storage_path() . '/model/baru.model', true)
	);
	$estimator->train($folds[0]);
	$estimator->partial($folds[1]);
	$estimator->partial($folds[2]);
	$estimator->partial($folds[3]);
	$estimator->save();
	// dump($estimator);
	$prediction = $estimator->predictSample(['alhamdulillah negatif covid lanjut']);
	dd($prediction);
});

Route::get('/cek2', function () {
	$estimator = PersistentModel::load(new Filesystem(storage_path() . '/model/test.model'));
	$prediction = $estimator->predictSample(['alhamdulillah negatif covid lanjut']);
	dd($prediction);
});

Route::get('/upload', [UploadTrainingController::class, 'index'])->name('upload.training.view');
Route::post('/upload', [UploadTrainingController::class, 'import'])->name('upload.training.create');

Route::get('/text-mining', [TextMiningController::class, 'index']);
Route::post('/text-mining', [TextMiningController::class, 'createModel'])->name('create.model');

Auth::routes();

Route::get('/home', 'App\Http\Controllers\HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
	Route::get('{page}', ['as' => 'page.index', 'uses' => 'App\Http\Controllers\PageController@index']);
});
