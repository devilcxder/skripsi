<?php

namespace App\Console\Commands;

use App\Crawling\CrawlingService;
use App\Models\Test;
use Illuminate\Console\Command;
use Thujohn\Twitter\Facades\Twitter;
use Sastrawi\Stemmer\StemmerFactory;
use Sastrawi\StopWordRemover\StopWordRemoverFactory;

class TweetCrawl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tweet:crawl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawling Tweet in real time';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {        
        CrawlingService::index('COVID-19',10);
    }
}
