<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Vedmant\FeedReader\Facades\FeedReader as FacadesFeedReader;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // default user
        \App\Models\User::factory(1)->create(['email' => 'demo@snugfeed.com']);
    
        // $feeds = ["https://www.smashingmagazine.com/feed/", "https://techcrunch.com/feed/", "https://feeds.macrumors.com/MacRumors-All", "https://lifehacker.com/rss"];

        // $f = FacadesFeedReader::read('https://news.google.com/news/rss');

        // echo $f->get_title();
        // echo $f->get_items()[0]->get_title();
        // echo $f->get_items()[0]->get_content();
        
        // foreach ($feeds as $feed) {
        //     \App\Models\Feed::factory(2)->has(Article::factory()->count(3))->create();
        // }
    }
}
