<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Feed;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Vedmant\FeedReader\Facades\FeedReader as FacadesFeedReader;
use Inertia\Inertia;
use Illuminate\Foundation\Application;

class FeedController extends Controller
{
  public function index()
  {
    if (auth()->check()) {
      $articles = Article::join('feeds', 'feeds.id', '=', 'articles.feed_id')
        ->select('articles.*', 'feeds.favicon')
        ->orderBy('posted_at', 'desc')
        ->get()
        ->map(function ($article) {
          return array_merge($article->toArray(), [
            'cleaned' => strip_tags($article->description),
          ]);
        });

      return Inertia::render('Feeds', [
        'feeds' => Feed::all(),
        'articles' => $articles,
      ]);
    }

    return Inertia::render('Home', [
      'canLogin' => Route::has('login'),
      'canRegister' => Route::has('register'),
      'laravelVersion' => Application::VERSION,
      'phpVersion' => PHP_VERSION,
    ]);
  }

  public function saved()
  {
    $articles = Article::join('feeds', 'feeds.id', '=', 'articles.feed_id')
      ->select('articles.*', 'feeds.favicon')
      ->orderBy('posted_at', 'desc')
      ->get()
      ->map(function ($article) {
        return array_merge($article->toArray(), [
          'cleaned' => strip_tags($article->description),
        ]);
      });

    return Inertia::render('Saved', ['articles' => $articles]);
  }

  public function store(Request $request)
  {
    $request->validate(['url' => 'required|url']);

    $feed = $this->readAndStoreFeed($request->get('url'));

    auth()
      ->user()
      ->feeds()
      ->syncWithoutDetaching($feed);

    return back();
  }

  public function readAndStoreFeed($rssUrl)
  {
    $f = FacadesFeedReader::read($rssUrl);
    $title = $f->get_title();
    $items = $f->get_items();
    $baseUrl = $f->get_base();
    $favicon = $f->get_favicon();

    $feed = Feed::updateOrCreate(
      ['rss_url' => $rssUrl],
      ['base_url' => $baseUrl, 'title' => $title, 'favicon' => $favicon]
    );

    foreach ($items as $item) {
      $content = $item->get_content();
      $date = $item->get_gmdate();
      $img = $this->scanForFeaturedImage($content);
      $feed->articles()->updateOrCreate(
        ['rss_id' => $item->get_id()],
        [
          'description' => $content,
          'title' => $item->get_title(),
          'img' => $img,
          'posted_at' => Carbon::parse($date, 'UTC'),
        ]
      );
    }

    return $feed;
  }

  public function scanForFeaturedImage($body)
  {
    $doc = new \DOMDocument();
    @$doc->loadHTML($body);

    $tags = $doc->getElementsByTagName('img');

    if (count($tags)) {
      return $tags[0]->getAttribute('src');
    }

    return null;
  }
}
