<?php

namespace App\Http\Controllers;

use App\Article;
use SimplePie;
use App\Feed as FeedModel;

use App\Http\Requests;

class Feed extends Controller
{
    public function getFeed($url) {
        //$url = 'http://feeds.feedburner.com/TechCrunch/';
        $feed = new SimplePie();
        $feed->set_feed_url($url);
        $feed->set_cache_location(dirname(dirname(dirname(dirname(__FILE__)))) . '/storage/framework/cache');
        $feed->init();
        $feed->handle_content_type();
        $arr = [];
        foreach ($feed->get_items() as $item) {

            $enclosure = $item->get_enclosure(0);

            $arr[] = [
                'des' => $item->get_description(),
                'title' => $item->get_title(),
                'thumb' => $enclosure->get_thumbnail()
            ];

        }
        return $arr;
    }

    public function getArticles() {
        return Article::limit(60)->orderBy('id', 'desc')->get();
    }

    public function getView() {
        return view('master', ['feeds' => FeedModel::all()]);
    }
}
