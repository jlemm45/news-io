<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Controllers\FeedController;

class FeedTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $feed = new FeedController();
        $test = $feed->lookForFavicon('http://rss.nytimes.com/services/xml/rss/nyt/Technology.xml');

        $this->assertTrue(true);
    }
}
