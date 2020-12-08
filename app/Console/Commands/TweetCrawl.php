<?php

namespace App\Console\Commands;

use App\Crawling\CrawlingService;
use Illuminate\Console\Command;

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
        CrawlingService::index('COVID',100);
    }
}
