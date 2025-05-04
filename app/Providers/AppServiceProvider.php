<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;

use App\Models\Advertisement;
use App\Observers\AdvertisementObserver;
use App\Observers\NewUserObserver;


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
        Advertisement::observe(AdvertisementObserver::class);
        User::observe(NewUserObserver::class);
    }
}
