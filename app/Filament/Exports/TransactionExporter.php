<?php

/**
 * Copyright (c) 2026 Jonathan
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */


namespace App\Filament\Exports;

use App\Models\Transaction;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class TransactionExporter extends Exporter
{
    protected static ?string $model = Transaction::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('transaction_date')
                ->label(__('Date')),
            ExportColumn::make('account.name')
                ->label(__('Account')),
            ExportColumn::make('type')
                ->label(__('Type')),
            ExportColumn::make('amount')
                ->label(__('Amount')),
            ExportColumn::make('category.name')
                ->label(__('Category')),
            ExportColumn::make('merchant.name')
                ->label(__('Merchant')),
            ExportColumn::make('description')
                ->label(__('Description')),
            ExportColumn::make('user.name')
                ->label(__('Autor')),
            ExportColumn::make('is_automated')
                ->label(__('Automated')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your transaction export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
