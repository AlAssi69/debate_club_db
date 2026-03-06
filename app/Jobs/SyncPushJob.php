<?php

namespace App\Jobs;

use App\Contracts\Syncable;
use App\Services\GoogleSheets\SyncPushService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncPushJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 10;

    public function __construct(
        protected Model $model,
        protected string $event,
    ) {}

    public function handle(SyncPushService $pushService): void
    {
        if (! config('services.google.sheet_id')) {
            return;
        }

        if (! ($this->model instanceof Syncable)) {
            return;
        }

        try {
            match ($this->event) {
                'deleted' => $pushService->pushDelete($this->model),
                default => $pushService->pushUpsert($this->model),
            };
        } catch (\Throwable $e) {
            Log::error("SyncPushJob failed for {$this->event}: {$e->getMessage()}");
            throw $e;
        }
    }
}
