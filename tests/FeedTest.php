<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Controllers\FeedController;
use SimplePie;

class FeedTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $feed = new SimplePie();
        $feed->set_feed_url('https://www.smashingmagazine.com/feed/');
        $feed->set_cache_location(dirname(dirname(__FILE__)) . '/storage/framework/cache');
        $feed->init();
        $feed->handle_content_type();
        $arr = [];

        $f = new FeedController();

        foreach ($feed->get_items() as $item) {

//            $enclosure = $item->get_enclosure(0);
//
//            $arr[] = [
//                'des' => $item->get_description(),
//                'title' => $item->get_title(),
//                'thumb' => $enclosure->get_thumbnail(),
//                'link' => $item->get_permalink()
//            ];
            $image = $f->scanForFeaturedImage($item->get_description());

            //print_r($image);

            echo $image;

            //echo $featuredImg['src'];
            echo '---------------------------------';

            //print_r($featuredImg);


            //print_r([0]);

        }

        //echo $feed->get_permalink();

        //$feed = new FeedController();

        //$test = $feed->lookForFavicon('http://rss.nytimes.com/services/xml/rss/nyt/Technology.xml');

        $this->assertTrue(true);
    }
}
