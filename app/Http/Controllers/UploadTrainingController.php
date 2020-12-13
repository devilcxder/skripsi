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
        $validated = request()->validate([
            'train' => 'required|mimes:xlsx,csv'
        ]);        
        Excel::import(new TrainsImport, request()->file('train'));
        return back()->with('status', 'Data Berhasil di Upload!');
    }
}
