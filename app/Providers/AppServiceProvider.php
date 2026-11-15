<?php

namespace App\Providers;

use App\Services\Auth\NextCloudUserProvider;
use App\Services\Nextcloud\NextCloudClient;
use App\Services\NextCloud\NextCloudWebDav;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
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
        Auth::provider('nextcloud', fn ($app, array $config) => new NextcloudUserProvider(
            $app->make(NextcloudClient::class),
            $app->make(NextCloudWebDav:: class)
        ));
    }
}
