<?php

namespace App\Crawling;

use App\Events\TweetSave;
use App\Jobs\TweetJob;
use App\Models\Tweet;
use App\Preprocessing\PreprocessingService;
use DateTime;
use DateTimeZone;
use Thujohn\Twitter\Facades\Twitter;
use TwitterStreamingApi;

class CrawlingService
{
    public static function index($keyword, $count)
    {
        // TwitterStreamingApi::publicStream()
        //     ->whenHears(['#COVID19','covid','corona'], function (array $tweet) {
        //         if (!isset($tweet['retweeted_status'])) {
        //             if (isset($tweet['extended_tweet'])) {
        //                 $text = $tweet['extended_tweet']['full_text'];
        //             } else {
        //                 $text = $tweet['text'];
        //             }                         
        //             dispatch(new TweetJob($tweet, $text));
        //         }
        //     })       
        //     ->setlocale('in')     
        //     ->startListening();
        //Last ID
        $last_id = Tweet::select('id_tweet')->orderBy('id', 'DESC')->first();
        if($last_id == null){
            $tweets = Twitter::getSearch(['q' => $keyword . ' -RT', 'tweet_mode' => 'extended', 'lang' => 'id', 'count' => $count, 'format' => 'array']);
        } else {
            $tweets = Twitter::getSearch(['q' => $keyword . ' -RT', 'tweet_mode' => 'extended', 'lang' => 'id', 'count' => $count, 'since_id' => intval($last_id['id_tweet']), 'format' => 'array']);
        }

        foreach ($tweets['statuses'] as $key => $value) {
            //Change format date and Time Zone to Asia/Jakarta
            $dt = DateTime::createFromFormat('D M d H:i:s P Y', $value['created_at'])->setTimezone(new DateTimeZone('Asia/Jakarta'));

            $filter_tweet[$key]['id_tweet'] = $value['id_str'];
            $filter_tweet[$key]['user'] = $value['user']['name'];
            $filter_tweet[$key]['tweet'] = $value['full_text'];
            $filter_tweet[$key]['created_at'] = $dt->format('Y-m-d H:i:s');
            $full_text[] = $value['full_text'];
        }
        $tweet_after_prepro = PreprocessingService::index($full_text);        
        if (count($filter_tweet) === count($tweet_after_prepro)) {
            for ($i = 0; $i < count($filter_tweet); $i++) {
                $filter_tweet[$i]['prepro_tweet'] = $tweet_after_prepro[$i];
            }
        }                
        Tweet::insert(array_reverse(($filter_tweet),true));
    }
}
