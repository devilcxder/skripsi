<?php

namespace App\Http\Controllers;

use App\Jobs\ModelJob;
use App\Models\Train;
use Illuminate\Http\Request;

class TextMiningController extends Controller
{    
    public function index()
    {
        return view('text_mining');
    }

    public function createModel()
    {                        
        $pembagian = floatval(request()->pembagian);        
        $model_name = request()->model_name;        
        dispatch(new ModelJob($pembagian, $model_name));
    }
}
