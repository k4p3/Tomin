<?php

/**
 * Copyright (c) 2026 Jonathan
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */


namespace App\Filament\Resources\InstallmentPurchases\Pages;

use App\Actions\ProcessInstallmentAction;
use App\DTOs\InstallmentPurchaseDTO;
use App\Filament\Resources\InstallmentPurchases\InstallmentPurchaseResource;
use App\Models\InstallmentPurchase;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateInstallmentPurchase extends CreateRecord
{
    protected static string $resource = InstallmentPurchaseResource::class;

    /**
     * Sobrescribir el método para procesar la compra a través del Action + DTO.
     */
    protected function handleRecordCreation(array $data): Model
    {
        // 1. Instanciar el DTO
        $dto = InstallmentPurchaseDTO::fromArray($data);
        
        // 2. Ejecutar el Action
        $action = new ProcessInstallmentAction();
        $transaction = $action->execute($dto);

        // 3. Retornar el registro de MSI creado (para que Filament haga el redirect)
        return InstallmentPurchase::where('transaction_id', $transaction->id)->first();
    }
}
