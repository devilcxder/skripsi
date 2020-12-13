<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\PredictController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadTrainingController;
use App\Models\Train;
use Illuminate\Support\Facades\Auth;
use Rubix\ML\Classifiers\GaussianNB;
use Rubix\ML\CrossValidation\Reports\AggregateReport;
use Rubix\ML\CrossValidation\Reports\ConfusionMatrix;
use Rubix\ML\CrossValidation\Reports\MulticlassBreakdown;
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

Route::get('/', [HomeController::class, 'index']);

Route::post('/upload-data-training', [UploadTrainingController::class, 'import'])->name('upload.data.training');

Route::post('/create-model', [ModelController::class, 'create'])->name('create.model');

Route::post('/predict', [PredictController::class, 'index'])->name('predict');


Route::get('/cek', function () {
	$samples = $labels = [];
	$data = Train::select('prepro_train', 'sentiment')->get();	
	foreach ($data as $train) {
		$samples[] = [$train->prepro_train];
		$labels[] = $train->sentiment;
	}
	$dataset = Labeled::build($samples, $labels);
	[$training, $testing] = $dataset->stratifiedSplit(0.7);
	// $folds = $training->fold(count($labels) / 100);
	$estimator = new PersistentModel(
        new Pipeline([
            new WordCountVectorizer(),
            new TfIdfTransformer(),
        ], new GaussianNB()),
        new Filesystem(storage_path() . '/model/ahay.model', true)
    );

	$estimator->train($training);

	// $estimator->train($folds[0]);
	// for ($i = 1; $i < count($folds); $i++) {
	//     $estimator->partial($folds[$i]);
	// }

	dd($estimator);

	$predictions = $estimator->predict($testing);

	// $estimator->save();	
	//Report    
	$report = new AggregateReport([
		new MulticlassBreakdown(),
		new ConfusionMatrix(),
	]);
	$results = $report->generate($predictions, $testing->labels());	
	dump($results);
});

Auth::routes();

// Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'], function () {
	Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
	Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
	Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);
	Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
	Route::get('{page}', ['as' => 'page.index', 'uses' => 'App\Http\Controllers\PageController@index']);
});
