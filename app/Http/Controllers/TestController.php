<?php

namespace App\Http\Controllers;

use App\Crawling\CrawlingService;

class TestController extends Controller
{
    public function index()
    {
        CrawlingService::index('COVID-19',10);
    }
}
