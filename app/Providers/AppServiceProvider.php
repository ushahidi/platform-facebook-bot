<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\FBMessenger;
use App\Services\PlatformApiService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(FBMessenger::class, function($app) {
            return new FBMessenger(config('options'));
        });
        $this->app->singleton(PlatformApiService::class, function($app) {
            return new PlatformApiService(config('options.ushahidi'));
        });
    }
}
