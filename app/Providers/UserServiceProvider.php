<?php

namespace App\Providers;

use App\Services\Implement\UserServiceImpl;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    $this->app->singleton(UserService::class, function () {
      return new UserServiceImpl();
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
