<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\CrawlFeed;
use Illuminate\Support\Facades\DB;

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

            //only allow feed id to be queued once at a time
            //$query = DB::table('jobs')->where('queue', '=', $url->id)->get();

            //if(count($query) == 0) {
                //assign the queue to be the feed id
                $job = (new CrawlFeed($url));
                $this->dispatch($job);
            //}
        }
    }
}
