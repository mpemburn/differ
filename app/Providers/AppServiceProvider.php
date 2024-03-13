<?php

namespace App\Providers;

use App\Helpers\Image;
use App\Helpers\Reader;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('image',function() {
            return new Image();
        });
        $this->app->bind('reader',function(){
            return new Reader();
        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
