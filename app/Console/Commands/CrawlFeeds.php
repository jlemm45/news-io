<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Vedmant\FeedReader\Facades\FeedReader as FacadesFeedReader;

class CrawlFeeds extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'crawl:feeds';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Crawl feeds';

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle()
  {
    $f = FacadesFeedReader::read('https://news.google.com/news/rss');
    dd($f->get_title());
  }
}
