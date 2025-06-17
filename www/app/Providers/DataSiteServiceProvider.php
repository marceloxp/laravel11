<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

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
            $datasite = app('datasite')->all();
            $datasite['datasite'] = true;
            $view->with('datasite', $datasite);
        });
    }
}
