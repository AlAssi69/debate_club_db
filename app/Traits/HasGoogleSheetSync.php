<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * Provides default implementations for the Syncable contract.
 * Auto-generates a UUID on model creation and exposes sync timestamp helpers.
 */
trait HasGoogleSheetSync
{
    public static function bootHasGoogleSheetSync(): void
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function getSyncUuid(): string
    {
        return $this->uuid;
    }

    public function getSyncedAt(): ?Carbon
    {
        return $this->synced_at;
    }

    public function markAsSynced(): void
    {
        $this->update(['synced_at' => now()]);
    }
}
