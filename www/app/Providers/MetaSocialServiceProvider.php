<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MetaSocialServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('metasocial', function () {
            return new \App\Services\MetaSocial;
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
