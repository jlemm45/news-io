<?php

namespace App\Http\Services;
use GuzzleHttp;

class GoogleFeed {

    static function query($term) {

        $client = new GuzzleHttp\Client();

        $response = $client->get('https://ajax.googleapis.com/ajax/services/feed/find?v=1.0&q='.$term);

        return $response->getBody();
    }

}