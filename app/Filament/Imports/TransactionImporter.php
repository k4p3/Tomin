<?php

/**
 * Copyright (c) 2026 Jonathan Bailon Segura <jonn59@gmail.com>
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */




namespace App\Filament\Imports;

use App\Models\Transaction;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Facades\Filament;

class TransactionImporter extends Importer
{
    protected static ?string $model = Transaction::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('transaction_date')
                ->label(__('Date'))
                ->requiredMapping(),
            ImportColumn::make('account')
                ->label(__('Account'))
                ->relationship()
                ->requiredMapping(),
            ImportColumn::make('type')
                ->label(__('Type'))
                ->requiredMapping(),
            ImportColumn::make('amount')
                ->label(__('Amount'))
                ->numeric()
                ->requiredMapping(),
            ImportColumn::make('description')
                ->label(__('Description')),
            ImportColumn::make('category')
                ->label(__('Category'))
                ->relationship(),
            ImportColumn::make('merchant')
                ->label(__('Merchant'))
                ->relationship(),
        ];
    }

    public function resolveRecord(): ?Transaction
    {
        $transaction = new Transaction();
        $transaction->wallet_id = Filament::getTenant()->id;
        $transaction->user_id = auth()->id();

        return $transaction;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your transaction import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
