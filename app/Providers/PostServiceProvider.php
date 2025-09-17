<?php

namespace App\Providers;

use App\Services\Implement\PostServiceImpl;
use App\Services\PostService;
use Illuminate\Support\ServiceProvider;

class PostServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    $this->app->singleton(PostService::class, function () {
      return new PostServiceImpl();
    });
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    //
  }
}
