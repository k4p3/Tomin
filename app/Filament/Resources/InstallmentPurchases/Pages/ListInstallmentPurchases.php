<?php

namespace App\Filament\Resources\InstallmentPurchases\Pages;

use App\Filament\Resources\InstallmentPurchases\InstallmentPurchaseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInstallmentPurchases extends ListRecords
{
    protected static string $resource = InstallmentPurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
