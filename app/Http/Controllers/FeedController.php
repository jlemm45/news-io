<?php

namespace App\Http\Controllers;

use App\Article;
use SimplePie;
use App\Feed as FeedModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;

class FeedController extends Controller
{
    public function getFeed($url) {
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
        if(isset($_GET['article-ids'])) {
            $ids = explode(',', $_GET['article-ids']);
            return Article::find($ids);
        }
        //return Article::limit(60)->orderBy('id', 'desc')->get();
        $where = isset($_GET['start']) ? ['articles.id', '<', $_GET['start']] : ['articles.id', '>', 0];
        $ids = explode(',', $_GET['ids']);
        return DB::table('articles')
            ->join('feeds', 'feed_id', '=', 'feeds.id')
            ->select('feeds.icon_name', 'articles.feed_id', 'articles.id', 'article_title', 'article_img',
                'article_description')
            ->whereIn('feeds.id', $ids)
            ->where($where[0], $where[1], $where[2])
            ->orderBy('articles.id', 'desc')
            ->limit(20)
            ->get();
    }

    public function feedsView() {
        $user = false;
        if (Auth::user())
        {
            $user = Auth::user();
        }
        return view('pages.feeds', ['feeds' => FeedModel::all(), 'user' => $user]);
    }

    public function welcomeView() {
        return view('pages.welcome');
    }
}
