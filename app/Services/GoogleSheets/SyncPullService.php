<?php

namespace App\Services\GoogleSheets;

use App\Contracts\Syncable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncPullService
{
    public function __construct(
        protected GoogleSheetsClient $client,
        protected ConflictResolver $conflictResolver,
    ) {}

    /**
     * Pull all rows from a sheet and upsert into the local database.
     * Uses timestamp-based conflict resolution: remote overwrites local only when newer.
     *
     * @param  class-string<Model&Syncable>  $modelClass
     */
    public function pull(string $modelClass): int
    {
        /** @var Model&Syncable $instance */
        $instance = new $modelClass;
        $sheetName = $instance->getSheetName();
        $rows = $this->client->readSheet($sheetName);

        if (empty($rows)) {
            return 0;
        }

        $synced = 0;

        // Skip header row
        $dataRows = array_slice($rows, 1);

        DB::transaction(function () use ($dataRows, $modelClass, &$synced) {
            foreach ($dataRows as $row) {
                if (empty($row[0])) {
                    continue;
                }

                $uuid = $row[0];
                $remoteTimestamp = end($row) ?: null;

                /** @var (Model&Syncable)|null $existing */
                $existing = $modelClass::where('uuid', $uuid)->first();

                if ($existing && ! $this->conflictResolver->remoteIsNewer($existing, $remoteTimestamp)) {
                    continue;
                }

                $model = $modelClass::fromSheetRow($row);
                $model->markAsSynced();
                $synced++;
            }
        });

        Log::info("Pulled {$synced} rows from {$sheetName}");

        return $synced;
    }
}
