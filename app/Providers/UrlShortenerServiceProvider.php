<?php

namespace App\Providers;

use App\Contracts\UrlShortenerContract;
use App\Services\UrlShortenerService;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class UrlShortenerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->scoped(UrlShortenerContract::class, function (Application $app) {
            return $app->make(config('services.urlShortener.class'));
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
