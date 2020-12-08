<?php

namespace App\Preprocessing;

use Sastrawi\Stemmer\StemmerFactory;
use Sastrawi\StopWordRemover\StopWordRemoverFactory;

class PreprocessingService
{
    public static function index(array $tweets)
    {        
        //PREPROCESSING
        $stemmerFactory = new StemmerFactory();
        $stopWordFactory = new StopWordRemoverFactory();
        $stemmer  = $stemmerFactory->createStemmer();
        $stopword = $stopWordFactory->createStopWordRemover();        

        //REMOVE URL # @
        $regex = "/\b((https?|ftp|file):\/\/|www\.)[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i";

        foreach($tweets as $tweet){
            $text = preg_replace(array($regex, '/#\w+\s*/', '/@\w+\s*/'), '', $tweet);
    
            //STEMMING        
            $stemming =  $stemmer->stem($text);
    
            //STOPWORD REMOVAL
            $stopWord = $stopword->remove($stemming);
    
            //FINAL PREPROCESSING
            $prepro = trim(str_replace(array("\n", "\r"), " ", $stopWord));
            $result = strtolower(preg_replace("/[^a-zA-Z\s]/", "", $prepro));
            $result = preg_replace("/\s\s+/", " ", $result);
            $result = rtrim($result, " ");
            $tweet_after_prepro [] = $result;
        }        
        return $tweet_after_prepro;
    }
}
