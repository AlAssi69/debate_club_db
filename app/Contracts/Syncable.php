<?php

namespace App\Contracts;

use Carbon\Carbon;

interface Syncable
{
    public function getSyncUuid(): string;

    public function getSheetName(): string;

    public function toSheetRow(): array;

    public static function fromSheetRow(array $row): static;

    public function getSyncedAt(): ?Carbon;
}
