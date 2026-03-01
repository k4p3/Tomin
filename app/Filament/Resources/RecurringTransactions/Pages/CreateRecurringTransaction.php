<?php

namespace App\Filament\Resources\RecurringTransactions\Pages;

use App\Actions\CreateRecurringTransactionAction;
use App\DTOs\RecurringTransactionDTO;
use App\Filament\Resources\RecurringTransactions\RecurringTransactionResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateRecurringTransaction extends CreateRecord
{
    protected static string $resource = RecurringTransactionResource::class;

    /**
     * Sobrescribir el método para procesar la automatización a través del Action + DTO.
     */
    protected function handleRecordCreation(array $data): Model
    {
        // 1. Instanciar el DTO
        $dto = RecurringTransactionDTO::fromArray($data);
        
        // 2. Ejecutar el Action
        return (new CreateRecurringTransactionAction())->execute($dto);
    }
}
