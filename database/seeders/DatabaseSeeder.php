<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\FeedService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    if (User::count()) {
      Log::info('Database has data. Not seeding...');

      return;
    }

    // default user
    User::factory(1)->create(['email' => 'demo@snugfeed.com']);

    $feedService = App::make(FeedService::class);

    $feeds = [
      'https://www.smashingmagazine.com/feed/',
      'https://techcrunch.com/feed/',
      'https://feeds.macrumors.com/MacRumors-All',
      'https://lifehacker.com/rss',
    ];

    foreach ($feeds as $feed) {
      $feedService->crawlUrl($feed);
    }
  }
}
