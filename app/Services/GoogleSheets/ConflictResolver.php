<?php

namespace App\Services\GoogleSheets;

use App\Contracts\Syncable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ConflictResolver
{
    /**
     * Determine whether the remote (Google Sheets) version is newer than local.
     *
     * @param  Model&Syncable  $localModel
     * @param  string|null     $remoteTimestamp  ISO 8601 timestamp from the sheet
     */
    public function remoteIsNewer(Model $localModel, ?string $remoteTimestamp): bool
    {
        if (! $remoteTimestamp) {
            return false;
        }

        $remoteTime = Carbon::parse($remoteTimestamp);
        $localTime = $localModel->updated_at;

        if (! $localTime) {
            return true;
        }

        return $remoteTime->isAfter($localTime);
    }

    /**
     * Determine whether the local version is newer than remote.
     *
     * @param  Model&Syncable  $localModel
     * @param  string|null     $remoteTimestamp  ISO 8601 timestamp from the sheet
     */
    public function localIsNewer(Model $localModel, ?string $remoteTimestamp): bool
    {
        if (! $remoteTimestamp) {
            return true;
        }

        $remoteTime = Carbon::parse($remoteTimestamp);
        $localTime = $localModel->updated_at;

        if (! $localTime) {
            return false;
        }

        return $localTime->isAfter($remoteTime);
    }
}
