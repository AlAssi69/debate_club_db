<?php

namespace App\Jobs;

use App\Models\Debate;
use App\Models\Person;
use App\Models\TrainingSession;
use App\Services\GoogleSheets\SyncPullService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncPullJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 10;

    /** @var array<int, class-string> */
    protected static array $syncableModels = [
        Person::class,
        TrainingSession::class,
        Debate::class,
    ];

    public function handle(SyncPullService $pullService): void
    {
        if (! config('services.google.sheet_id')) {
            Log::warning('SyncPullJob skipped: GOOGLE_SHEET_ID not configured.');

            return;
        }

        $totalSynced = 0;

        foreach (static::$syncableModels as $modelClass) {
            try {
                $totalSynced += $pullService->pull($modelClass);
            } catch (\Throwable $e) {
                Log::error("SyncPullJob failed for {$modelClass}: {$e->getMessage()}");
            }
        }

        Log::info("SyncPullJob completed. Total rows synced: {$totalSynced}");
    }
}
