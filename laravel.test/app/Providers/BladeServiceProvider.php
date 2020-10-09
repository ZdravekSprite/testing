<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Blade;
use Auth;

class BladeServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   *
   * @return void
   */
  public function register()
  {
    //
  }

  /**
   * Bootstrap services.
   *
   * @return void
   */
  public function boot()
  {
    Blade::if('hasrole', function ($expression) {
      if (Auth::user()) {
        if (Auth::user()->hasAnyRole($expression)) {
          return true;
        }
      }
      return false;
    });
  }
}
