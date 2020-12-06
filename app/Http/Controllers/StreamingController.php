<?php

namespace App\Http\Controllers;

use App\Jobs\TweetJob;
use Illuminate\Http\Request;
use TwitterStreamingApi;

class StreamingController extends Controller
{
    public function index()
    {        
        TwitterStreamingApi::publicStream()
            ->whenHears('#COVID19', function (array $tweet) {
                if(!isset($tweet['retweeted_status'])){
                    dd($tweet['user']['name']);
                }                
                // echo "{$tweet['user']['screen_name']} tweeted {$tweet['text']}";
            })            
            ->startListening();
    }
}
