<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\CrawlFeed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\FeedController;

class CheckFeeds extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feeds:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks feeds in the feeds table and adds new articles.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $urls = \App\Feed::all();

        foreach ($urls as $url) {

            $feedCtrl = new FeedController();

            $feeds = $feedCtrl->getFeed($url->feed_url);

            //echo 'before--------';
            if(Cache::get($url->feed_url) !== $feeds) {
                Cache::put($url->feed_url, $feeds, 60);

                //echo 'different--------';

                $job = (new CrawlFeed($url));
                $this->dispatch($job);

            }
        }
    }
}
