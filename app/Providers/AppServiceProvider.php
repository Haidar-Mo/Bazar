<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use OpenSoutheners\LaravelCompanionApps\CompanionApplication;
use OpenSoutheners\LaravelCompanionApps\Platform;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \OpenSoutheners\LaravelCompanionApps\ServiceProvider::loadApplications([
            CompanionApplication::make('com.example.bazar', Platform::Android)
                ->linkScheme('example'),

            CompanionApplication::make('com.example_preview', Platform::Android)
                ->linkScheme('example'),

            CompanionApplication::make('com.example', Platform::Apple)
                ->linkScheme('example')
                ->setStoreOptions(id: '123456789', slug: 'example_app')
        ]);
    }
}
