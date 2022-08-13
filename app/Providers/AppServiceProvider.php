<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    $this->app->bind(
      \App\Repositories\User_RepositoryInterface::class,
      \App\Repositories\User_Repository::class,
    );
    $this->app->bind(
      \App\Services\User_ServiceInterface::class,
      \App\Services\User_Service::class,
    );
    $this->app->bind(
      \App\Repositories\Exercise_RepositoryInterface::class,
      \App\Repositories\Exercise_Repository::class,
    );
    $this->app->bind(
      \App\Services\Exercise_ServiceInterface::class,
      \App\Services\Exercise_Service::class,
    );
    $this->app->bind(
      \App\Repositories\E_Setting_RepositoryInterface::class,
      \App\Repositories\E_Setting_Repository::class,
    );
    $this->app->bind(
      \App\Services\Setting_ServiceInterface::class,
      \App\Services\Setting_Service::class,
    );
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    Paginator::useBootstrap();
  }
}
