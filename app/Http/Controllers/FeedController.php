<?php

namespace App\Http\Controllers;

use App\Article;
use SimplePie;
use App\Feed as FeedModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Time;
use GuzzleHttp;
use FastImage;

use App\Http\Requests;

class FeedController extends Controller
{
    var $cache;

    public function __construct()
    {
        //have to define a cache location
        $this->cache = dirname(dirname(dirname(dirname(__FILE__)))) . '/storage/framework/cache';
    }

    /**
     * Get an rss feed using simple pie
     *
     * @param $url
     * @return array
     */
    public function getFeed($url) {
        $feed = new SimplePie();
        $feed->set_feed_url($url);
        $feed->set_cache_location($this->cache);
        $feed->init();
        $feed->handle_content_type();
        $arr = [];

        foreach ($feed->get_items() as $item) {

            $enclosure = $item->get_enclosure(0);

            $des = $item->get_description();

            $arr[] = [
                'des' => $des,
                'title' => $item->get_title(),
                'thumb' => $enclosure->get_thumbnail(),
                'link' => $item->get_permalink()
            ];

        }
        return $arr;
    }

    /**
     * Scans a body of html and returns the widest img
     *
     * @param $body
     * @return array
     */
    public function scanForFeaturedImage($body) {
        $doc = new \DOMDocument();
        @$doc->loadHTML($body);

        $tags = $doc->getElementsByTagName('img');

        $featuredImg = null;
        $featuredWidth = 100;
        $featuredHeight = 50;
        foreach ($tags as $tag) {
            $src = $tag->getAttribute('src');

            try{
                $image = new FastImage($src);
                list($width, $height) = $image->getSize();
            }
            catch(\ErrorException $e) {
                $width = 0;
                $height = 0;
            }

            if($width > $featuredWidth && $height > $featuredHeight) {
                $featuredWidth = $width;
                $featuredHeight = $height;
                $featuredImg = $src;
            }
        }
        return $featuredImg;
    }

    public function getBaseUrl($url) {
        $info = parse_url($url);
        $arr = explode('.',$info['host']);

        $base = $arr[count($arr)-2];
        $ending = end($arr);

        return 'http://'.$base.'.'.$ending;
    }

    /**
     * Verify and return feed if valid
     *
     * @param $url
     * @return bool|SimplePie
     */
    public function checkIfFeedIsValid($url) {
        $feed = new SimplePie();
        $feed->set_feed_url($url);
        $feed->set_cache_location($this->cache);
        $feed->init();
        $feed->handle_content_type();

        if($feed->error()) {
            return false;
        }
        return $feed;
    }

    /**
     * Return the view for the feeds page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function feedsView() {
        $user = false;
        if (Auth::user())
        {
            $user = Auth::user();
        }
        return view('pages.feeds', ['feeds' => FeedModel::all(), 'user' => $user]);
    }

    /**
     * Welcome page view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function welcomeView() {
        return view('pages.welcome');
    }
}
