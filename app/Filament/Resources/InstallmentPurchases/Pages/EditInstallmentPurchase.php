<?php

namespace App\Filament\Resources\InstallmentPurchases\Pages;

use App\Filament\Resources\InstallmentPurchases\InstallmentPurchaseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInstallmentPurchase extends EditRecord
{
    protected static string $resource = InstallmentPurchaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
