<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArtisanController extends Controller
{
    public function handle()
    {
        shell_exec('php '.base_path('artisan').' schedule:run > /dev/null 2>/dev/null &');
    }
}
