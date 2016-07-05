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

            //$img = !empty($des) ? $this->scanForFeaturedImage($des) : null;

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
        foreach ($tags as $tag) {
            $src = $tag->getAttribute('src');

            try{
                $image = new FastImage($src);
                list($width, $height) = $image->getSize();
            }
            catch(\ErrorException $e) {
                $width = 0;
            }

            if($width > $featuredWidth) {
                $featuredWidth = $width;
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
     * Main method to return article data to the front
     *
     * @return mixed
     */
    public function getArticles() {
        $articles = $this->getArticlesFromDB();

        if(is_object($articles)) return $articles;

        $featuredChosen = false;
        $newArr = $articles;

        foreach($articles as $key => $article) {
            $des = $article->article_description;
            $article->article_description = $this->stripATags($des);
            $article->article_title = htmlspecialchars_decode($article->article_title);
            //$article->time_ago = Time::timePassed($article->created_at);
            $article->created_at = Time::utcToCentral($article->created_at);
            //$article->icon_url = 'https://www.google.com/s2/favicons?domain='.$article

            if($article->article_img && !$featuredChosen) {
                $article->featured = true;
                $featuredChosen = true;
                array_unshift($newArr, $article);
                unset($newArr[$key+1]);
            }
        }

        return array_values($newArr);
    }

    private function getArticlesFromDB() {
        if(isset($_GET['article-ids'])) {
            $ids = explode(',', $_GET['article-ids']);
            return Article::find($ids);
        }
        //return Article::limit(60)->orderBy('id', 'desc')->get();
        $where = isset($_GET['start']) ? ['articles.id', '<', $_GET['start']] : ['articles.id', '>', 0];
        $ids = explode(',', $_GET['ids']);
        return DB::table('articles')
            ->join('feeds', 'feed_id', '=', 'feeds.id')
            ->select('articles.feed_id', 'article_link', 'articles.created_at', 'articles.id', 'article_title', 'article_img',
                'article_description')
            ->whereIn('feeds.id', $ids)
            ->where($where[0], $where[1], $where[2])
            ->orderBy('articles.id', 'desc')
            ->limit(20)
            ->get();
    }

    /**
     * Strip out anchor tags from html
     *
     * @param $description
     * @return mixed
     */
    private function stripATags($description) {
        return strip_tags($description,"<p>");
        return preg_replace('/<a\b[^>]*>(.*?)<\/a>/i', '', $description);
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
