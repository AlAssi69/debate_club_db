<?php

namespace App\Services\GoogleSheets;

use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;

class GoogleSheetsClient
{
    private ?Sheets $service = null;

    private string $spreadsheetId;

    public function __construct()
    {
        $this->spreadsheetId = config('services.google.sheet_id', '');
    }

    protected function getService(): Sheets
    {
        if ($this->service) {
            return $this->service;
        }

        $client = new Client();
        $client->setScopes([Sheets::SPREADSHEETS]);

        $credentialsPath = config('services.google.service_account_json');
        if ($credentialsPath && file_exists($credentialsPath)) {
            $client->setAuthConfig($credentialsPath);
        }

        $this->service = new Sheets($client);

        return $this->service;
    }

    public function getSpreadsheetId(): string
    {
        return $this->spreadsheetId;
    }

    /**
     * Read all rows from a given sheet tab.
     *
     * @return array<int, array<int, string>>
     */
    public function readSheet(string $sheetName): array
    {
        $response = $this->getService()->spreadsheets_values->get(
            $this->spreadsheetId,
            $sheetName
        );

        return $response->getValues() ?? [];
    }

    /**
     * Append a row to the given sheet tab.
     */
    public function appendRow(string $sheetName, array $values): void
    {
        $body = new ValueRange(['values' => [$values]]);

        $this->getService()->spreadsheets_values->append(
            $this->spreadsheetId,
            $sheetName,
            $body,
            ['valueInputOption' => 'USER_ENTERED']
        );
    }

    /**
     * Update a specific row by range (e.g., "Persons!A2:G2").
     */
    public function updateRow(string $range, array $values): void
    {
        $body = new ValueRange(['values' => [$values]]);

        $this->getService()->spreadsheets_values->update(
            $this->spreadsheetId,
            $range,
            $body,
            ['valueInputOption' => 'USER_ENTERED']
        );
    }

    /**
     * Clear a specific row range (used for deletions).
     */
    public function clearRow(string $range): void
    {
        $this->getService()->spreadsheets_values->clear(
            $this->spreadsheetId,
            $range,
            new Sheets\ClearValuesRequest()
        );
    }

    /**
     * Find the row index (1-based) of a UUID in a sheet.
     * Returns null if not found. Row 1 is assumed to be headers.
     */
    public function findRowByUuid(string $sheetName, string $uuid): ?int
    {
        $rows = $this->readSheet($sheetName);

        foreach ($rows as $index => $row) {
            if ($index === 0) {
                continue;
            }
            if (isset($row[0]) && $row[0] === $uuid) {
                return $index + 1;
            }
        }

        return null;
    }
}
