<?php

namespace App\Services;

use App\Models\Feed;
use Carbon\Carbon;
use Vedmant\FeedReader\Facades\FeedReader as FacadesFeedReader;

class FeedService
{
  public function crawlUrl($url)
  {
    $f = FacadesFeedReader::read($url);
    $title = $f->get_title();
    $items = $f->get_items();
    $baseUrl = $f->get_base();
    $favicon = $f->get_favicon();

    $feed = Feed::updateOrCreate(
      ['rss_url' => $url],
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
  }

  private function scanForFeaturedImage($body)
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
