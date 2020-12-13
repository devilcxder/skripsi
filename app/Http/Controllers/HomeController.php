<?php

namespace App\Http\Controllers;

use App\Models\Modell;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {        
        $models = Modell::select('model')->get();        
        return view('home', ['models' => $models]);
    }
}
