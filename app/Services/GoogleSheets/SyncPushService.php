<?php

namespace App\Services\GoogleSheets;

use App\Contracts\Syncable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class SyncPushService
{
    public function __construct(
        protected GoogleSheetsClient $client,
        protected ConflictResolver $conflictResolver,
    ) {}

    /**
     * Push a created or updated model to Google Sheets.
     *
     * @param  Model&Syncable  $model
     */
    public function pushUpsert(Model $model): void
    {
        $sheetName = $model->getSheetName();
        $uuid = $model->getSyncUuid();
        $rowData = $model->toSheetRow();

        $existingRow = $this->client->findRowByUuid($sheetName, $uuid);

        if ($existingRow !== null) {
            $lastCol = chr(ord('A') + count($rowData) - 1);
            $range = "{$sheetName}!A{$existingRow}:{$lastCol}{$existingRow}";
            $this->client->updateRow($range, $rowData);
        } else {
            $this->client->appendRow($sheetName, $rowData);
        }

        $model->markAsSynced();

        Log::info("Pushed {$sheetName} row for UUID {$uuid}");
    }

    /**
     * Remove a row from Google Sheets when a model is deleted.
     *
     * @param  Model&Syncable  $model
     */
    public function pushDelete(Model $model): void
    {
        $sheetName = $model->getSheetName();
        $uuid = $model->getSyncUuid();

        $existingRow = $this->client->findRowByUuid($sheetName, $uuid);

        if ($existingRow !== null) {
            $this->client->clearRow("{$sheetName}!A{$existingRow}:Z{$existingRow}");
            Log::info("Cleared {$sheetName} row {$existingRow} for UUID {$uuid}");
        }
    }
}
