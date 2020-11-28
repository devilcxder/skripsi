<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TwitterStreamingApi;

class StreamingController extends Controller
{
    public function index()
    {        
        TwitterStreamingApi::publicStream()
            ->whenHears('#COVID19', function (array $tweet) {
                if(!isset($tweet['retweeted_status'])){
                    dd($tweet);
                }                
                // echo "{$tweet['user']['screen_name']} tweeted {$tweet['text']}";
            })            
            ->startListening();
    }
}
