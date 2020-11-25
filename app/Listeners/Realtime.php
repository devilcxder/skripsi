<?php

namespace App\Listeners;

use App\Events\TweetSave;
use App\Models\Tweet;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Pusher\Pusher;

class Realtime
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TweetSave  $event
     * @return void
     */
    public function handle(TweetSave $event)
    {
        $pusher = new Pusher(
            "d6dc82355927033668cd",    // Replace with 'key' from dashboard
            "1a05b6ef93690623d2cd", // Replace with 'secret' from dashboard
            "1092700",     // Replace with 'app_id' from dashboard
            array(
                'cluster' => 'ap1' // Replace with 'cluster' from dashboard
            )
        );

        // Trigger a new random event every second. In your application,
        // you should trigger the event based on real-world changes!

        $pusher->trigger('price-btcusd', 'new-price', array(
            'value' => [Tweet::all()->count(),rand(100,500)],            
        ));
    }
}
