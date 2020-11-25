<?php

namespace App\Crawling;

use App\Events\TweetSave;
use App\Models\Tweet;
use App\Preprocessing\PreprocessingService;
use Thujohn\Twitter\Facades\Twitter;
use TwitterStreamingApi;

class CrawlingService
{
    public static function index($keyword, $count)
    {
        TwitterStreamingApi::publicStream()
            ->whenHears('#COVID19', function (array $tweet) {
                if (!isset($tweet['retweeted_status'])) {
                    if (isset($tweet['extended_tweet'])) {
                        $text = $tweet['extended_tweet']['full_text'];
                    } else {
                        $text = $tweet['text'];
                    }                    
                    $result = PreprocessingService::index($text);

                    //save to array
                    $tweetsAfterPrepo[] = ['id_tweet' => $tweet['id_str'], 'tweet' => $text, 'prepro_tweet' => $result];
                    Tweet::insert($tweetsAfterPrepo);
                    event(new TweetSave());
                }
            })            
            ->startListening();
        // $tweets = Twitter::getSearch(['q' => $keyword . ' -RT', 'tweet_mode' => 'extended', 'lang' => 'id', 'count' => $count, 'format' => 'array']);

        // foreach ($tweets['statuses'] as $key => $value) {
        //     //here preprocessing step
        //     $result = PreprocessingService::index($value['full_text']);

        //     //save to array
        //     $tweetsAfterPrepo[] = ['id_tweet' => $value['id_str'], 'tweet' => $value['full_text'], 'prepro_tweet' => $result];
        // }
        // Tweet::insert($tweetsAfterPrepo);
        // event(new TweetSave());
    }
}
