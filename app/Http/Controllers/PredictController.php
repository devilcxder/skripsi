<?php

namespace App\Http\Controllers;

use App\Preprocessing\PreprocessingService;
use Illuminate\Http\Request;
use Rubix\ML\PersistentModel;
use Rubix\ML\Persisters\Filesystem;

class PredictController extends Controller
{
    public function index()
    {
        $validated = request()->validate([
            'tweet' => 'required|max:240',
            'model' => 'required'
        ]);        
        $pre_pro = PreprocessingService::index([$validated['tweet']]);        
        $estimator = PersistentModel::load(new Filesystem(storage_path() . '/model/'. $validated['model'] .'.model'));        
        $prediction = $estimator->predictSample($pre_pro);
        return back()->with('prediction', 'Prediksi: ' . $prediction);
    }
}
