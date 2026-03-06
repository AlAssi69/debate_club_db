<?php

namespace App\Providers;

use App\Services\GoogleSheets\ConflictResolver;
use App\Services\GoogleSheets\GoogleSheetsClient;
use App\Services\GoogleSheets\SyncPullService;
use App\Services\GoogleSheets\SyncPushService;
use Illuminate\Support\ServiceProvider;

class GoogleSheetsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(GoogleSheetsClient::class);
        $this->app->singleton(ConflictResolver::class);

        $this->app->singleton(SyncPushService::class, function ($app) {
            return new SyncPushService(
                $app->make(GoogleSheetsClient::class),
                $app->make(ConflictResolver::class),
            );
        });

        $this->app->singleton(SyncPullService::class, function ($app) {
            return new SyncPullService(
                $app->make(GoogleSheetsClient::class),
                $app->make(ConflictResolver::class),
            );
        });
    }
}
