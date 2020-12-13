<?php

namespace App\Crawling;

use App\Events\TweetSave;
use App\Jobs\TweetJob;
use App\Models\Emotion;
use App\Models\Modell;
use App\Models\Tweet;
use App\Preprocessing\PreprocessingService;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\DB;
use Rubix\ML\PersistentModel;
use Rubix\ML\Persisters\Filesystem;
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
        if ($last_id == null) {
            $tweets = Twitter::getSearch(['q' => $keyword . ' -RT', 'tweet_mode' => 'extended', 'lang' => 'id', 'count' => $count, 'format' => 'array']);
        } else {
            $tweets = Twitter::getSearch(['q' => $keyword . ' -RT', 'tweet_mode' => 'extended', 'lang' => 'id', 'count' => $count, 'since_id' => intval($last_id['id_tweet']), 'format' => 'array']);
        }

        foreach (array_reverse($tweets['statuses']) as $key => $value) {
            //Change format date and Time Zone to Asia/Jakarta
            $dt = DateTime::createFromFormat('D M d H:i:s P Y', $value['created_at'])->setTimezone(new DateTimeZone('Asia/Jakarta'));

            $filter_tweet[$key]['id_tweet'] = $value['id_str'];
            $filter_tweet[$key]['user'] = $value['user']['name'];
            $filter_tweet[$key]['tweet'] = $value['full_text'];
            $filter_tweet[$key]['created_at'] = $dt->format('Y-m-d H:i:s');
            $full_text[] = $value['full_text'];
        }        
        
        $tweet_after_prepro = PreprocessingService::index($full_text);

        //Choose model
        $model = Modell::select('model')->orderBy('id', 'DESC')->first();        
        $estimator = PersistentModel::load(new Filesystem(storage_path() . '/model/'. $model->model .'.model'));        
        if (count($filter_tweet) === count($tweet_after_prepro)) {                        
            foreach($filter_tweet as $key => $tweet){                        
                $insert_tweet = new Tweet;
                $insert_prediction = new Emotion;                    
                $insert_tweet->id_tweet = $tweet['id_tweet'];
                $insert_tweet->user = $tweet['user'];
                $insert_tweet->tweet = $tweet['tweet'];
                $insert_tweet->prepro_tweet = $tweet_after_prepro[$key];
                $insert_tweet->created_at = $tweet['created_at'];
                $insert_tweet->save();
                
                // Prediction
                $emotion = $estimator->predictSample([$tweet_after_prepro[$key]]);                

                $insert_prediction->emotion = $emotion;
                $insert_tweet->emotion()->save($insert_prediction);
            }            
        }
    }
}