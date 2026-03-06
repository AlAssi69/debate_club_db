<?php

namespace App\Providers;

use App\Models\Debate;
use App\Models\Person;
use App\Models\TrainingSession;
use App\Observers\SyncObserver;
use Illuminate\Database\Eloquent\Model;
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
        Model::preventLazyLoading(! $this->app->isProduction());

        Person::observe(SyncObserver::class);
        TrainingSession::observe(SyncObserver::class);
        Debate::observe(SyncObserver::class);
    }
}
