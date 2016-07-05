<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Controllers\FeedController;
use Illuminate\Support\Facades\Cache;
use App\Article;
use App\Http\Controllers\SocketController;

class CrawlFeed extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    var $url;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $newArticles = false;
        $newFeedIds = [];

        $feedCtrl = new FeedController();

        $feeds = $feedCtrl->getFeed($this->url->feed_url);

        //echo 'before--------';
        if(Cache::get($this->url->feed_url) !== $feeds) {
            Cache::put($this->url->feed_url, $feeds, 60);

            //echo 'cache different';

            $newArticleIds = [];
            foreach ($feeds as $feed) {
                if (!Article::where('article_title', '=', $feed['title'])->count() > 0) {
                    $article = new Article();
                    $article->article_description = $feed['des'];
                    $article->article_title = $feed['title'];
                    $article->article_img = !empty($feed['des']) ? $feedCtrl->scanForFeaturedImage($feed['des']) : null;
                    $article->article_link = $feed['link'];
                    $article->feed_id = $this->url->id;
                    $article->save();
                    $newArticleIds[] = $article->id;
                    $newArticles = true;
                }
            }
            if (count($newArticleIds) > 0) $newFeedIds[] = [$this->url->id => $newArticleIds];
        }
        if($newArticles) SocketController::pingSocketIO($newFeedIds);
    }
}
