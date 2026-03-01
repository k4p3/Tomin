<?php

/**
 * Copyright (c) 2026 Jonathan Bailon Segura <jonn59@gmail.com>
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */




namespace App\Filament\Resources\Transactions\Pages;

use App\Actions\CreateTransferAction;
use App\Filament\Resources\Transactions\TransactionResource;
use App\Filament\Exports\TransactionExporter;
use App\Filament\Imports\TransactionImporter;
use App\Models\Account;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Botón de Importación
            Actions\ImportAction::make()
                ->importer(TransactionImporter::class)
                ->label(__('Import'))
                ->fileUploadOption('acceptedFileTypes', ['text/csv', 'application/vnd.ms-excel'])
                ->fileUploadOption('directory', 'imports'),

            // Botón de Exportación
            Actions\ExportAction::make()
                ->exporter(TransactionExporter::class)
                ->label(__('Export')),

            // Botón de Nueva Transferencia
            Actions\Action::make('transfer')
                ->label(__('Transfer'))
                ->icon('heroicon-m-arrows-right-left')
                ->color('warning')
                ->form([
                    Select::make('from_account_id')
                        ->label(__('From Account'))
                        ->options(fn () => Account::all()->pluck('name', 'id'))
                        ->required()
                        ->searchable(),
                    
                    Select::make('to_account_id')
                        ->label(__('To Account'))
                        ->options(fn () => Account::all()->pluck('name', 'id'))
                        ->required()
                        ->searchable()
                        ->different('from_account_id'),

                    TextInput::make('amount')
                        ->label(__('Amount'))
                        ->numeric()
                        ->prefix('$')
                        ->required(),

                    DatePicker::make('transaction_date')
                        ->label(__('Date'))
                        ->default(now())
                        ->required(),

                    Toggle::make('is_shared')
                        ->label(__('Shared'))
                        ->default(true),
                ])
                ->action(function (array $data) {
                    $fromAccount = Account::find($data['from_account_id']);
                    $toAccount = Account::find($data['to_account_id']);

                    $data['from_account_name'] = $fromAccount->name;
                    $data['to_account_name'] = $toAccount->name;

                    $dto = \App\DTOs\TransferDTO::fromArray($data);

                    (new CreateTransferAction())->execute($dto);

                    Notification::make()
                        ->title(__('Transfer created successfully'))
                        ->success()
                        ->send();
                }),

            Actions\CreateAction::make(),
        ];
    }
}
