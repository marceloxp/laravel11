<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class DataSiteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('datasite', function () {
            return new \App\Services\DataSite;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        view()->composer('*', function ($view) {
            $view->with('datasite', app('datasite')->all());
        });
    }
}
