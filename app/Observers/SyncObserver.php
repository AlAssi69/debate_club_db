<?php

namespace App\Observers;

use App\Contracts\Syncable;
use App\Jobs\SyncPushJob;
use Illuminate\Database\Eloquent\Model;

/**
 * Generic observer that dispatches push-sync jobs for any Syncable model.
 * Registered once per syncable model in AppServiceProvider.
 */
class SyncObserver
{
    public function created(Model $model): void
    {
        $this->dispatchSyncJob($model, 'created');
    }

    public function updated(Model $model): void
    {
        $this->dispatchSyncJob($model, 'updated');
    }

    public function deleted(Model $model): void
    {
        $this->dispatchSyncJob($model, 'deleted');
    }

    protected function dispatchSyncJob(Model $model, string $event): void
    {
        if (! ($model instanceof Syncable)) {
            return;
        }

        if (! config('services.google.sheet_id')) {
            return;
        }

        SyncPushJob::dispatch($model, $event);
    }
}
