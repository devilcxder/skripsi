<?php

namespace App\Http\Controllers;

use App\Imports\TrainsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UploadTrainingController extends Controller
{
    public function index()
    {
        return view('upload_training');
    }

    public function import()
    {                
        Excel::import(new TrainsImport, request()->file('train'));
    }
}
