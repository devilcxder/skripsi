<?php

namespace App\Jobs;

use App\Events\TweetSave;
use App\Models\Tweet;
use App\Preprocessing\PreprocessingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TweetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tweet;
    protected $text;    

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tweet, $text)
    {
        $this->tweet = $tweet;
        $this->text = $text;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $result = PreprocessingService::index($this->text);

        //save to array
        $tweetsAfterPrepo[] = ['id_tweet' => $this->tweet['id_str'], 'user' => $this->tweet['user']['name'], 'tweet' => $this->text, 'prepro_tweet' => $result];        
        Tweet::insert($tweetsAfterPrepo);
        event(new TweetSave());
    }
}
