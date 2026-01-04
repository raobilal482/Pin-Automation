<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event; // Zaroori Line
use SocialiteProviders\Manager\SocialiteWasCalled; // Zaroori Line
use SocialiteProviders\Pinterest\PinterestExtendSocialite; // Zaroori Line

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
        // Pinterest Listener ko register karein
        Event::listen(
            SocialiteWasCalled::class,
            PinterestExtendSocialite::class
        );
    }
}
