<?php

namespace App\Preprocessing;

use Sastrawi\Stemmer\StemmerFactory;
use Sastrawi\StopWordRemover\StopWordRemoverFactory;

class PreprocessingService
{
    public static function index($text)
    {
        //PREPROCESSING
        $stemmerFactory = new StemmerFactory();
        $stopWordFactory = new StopWordRemoverFactory();
        $stemmer  = $stemmerFactory->createStemmer();
        $stopword = $stopWordFactory->createStopWordRemover();

        //REMOVE URL # @
        $regex = "/\b((https?|ftp|file):\/\/|www\.)[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i";
        $tweet = preg_replace(array($regex, '/#\w+\s*/', '/@\w+\s*/'), '', $text);

        //STEMMING        
        $stemming =  $stemmer->stem($tweet);

        //STOPWORD REMOVAL
        $stopWord = $stopword->remove($stemming);

        //FINAL PREPROCESSING
        $prepro = trim(str_replace(array("\n", "\r"), " ", $stopWord));
        $result = strtolower(preg_replace("/[^a-zA-Z\s]/", "", $prepro));
        $result = preg_replace("/\s\s+/", " ", $result);
        $result = rtrim($result, " ");

        return $result;
    }
}
