<?php

namespace App\Providers;

use App\Config\Config;
use App\Config\ConfigInterface;
use App\Service\Factory\PetStoreRequestFactory;
use App\Service\Factory\RequestFactoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ConfigInterface::class,
            Config::class
        );
        $this->app->bind(
            RequestFactoryInterface::class,
            PetStoreRequestFactory::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
