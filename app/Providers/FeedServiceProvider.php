<?php

namespace App\Providers;

use App\Services\FeedService;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class FeedServiceProvider extends ServiceProvider implements DeferrableProvider
{
  /**
   * Register services.
   *
   * @return void
   */
  public function register()
  {
    $this->app->singleton(FeedService::class, function ($app) {
      return new FeedService();
    });
  }

  /**
   * Get the services provided by the provider.
   *
   * @return array
   */
  public function provides()
  {
    return [FeedService::class];
  }
}
