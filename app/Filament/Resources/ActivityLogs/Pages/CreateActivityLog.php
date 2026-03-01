<?php

/**
 * Copyright (c) 2026 Jonathan
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */


namespace App\Filament\Resources\ActivityLogs\Pages;

use App\Filament\Resources\ActivityLogs\ActivityLogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateActivityLog extends CreateRecord
{
    protected static string $resource = ActivityLogResource::class;
}
