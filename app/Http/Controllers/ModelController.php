<?php

namespace App\Http\Controllers;

use App\Jobs\ModelJob;
use App\Models\Train;
use Illuminate\Http\Request;

class ModelController extends Controller
{
    public function create()
    {
        $dataset = Train::count();
        if($dataset == 0){
            return back()->with('model', 'Dataset masih kosong!');
        }
        $validated = request()->validate([
            'model' => 'required',
            'split' => 'required|integer|between:1,100'
        ]);        

        dispatch(new ModelJob($validated['model'], $validated['split']/100));
        return back()->with('model', 'Model sedang dibuat!');
    }
}
