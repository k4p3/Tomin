<?php

/**
 * Copyright (c) 2026 Jonathan
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */


namespace App\Filament\Resources\ActivityLogs\Pages;

use App\Filament\Resources\ActivityLogs\ActivityLogResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditActivityLog extends EditRecord
{
    protected static string $resource = ActivityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
