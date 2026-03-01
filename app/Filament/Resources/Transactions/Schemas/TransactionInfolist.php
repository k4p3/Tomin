<?php

/**
 * Copyright (c) 2026 Jonathan Bailon Segura <jonn59@gmail.com>
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */




namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use Filament\Schemas\Schema;

class TransactionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Transaction Details'))
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('transaction_date')
                                    ->label(__('Date'))
                                    ->date(),
                                TextEntry::make('amount')
                                    ->label(__('Amount'))
                                    ->money('MXN'),
                                TextEntry::make('type')
                                    ->label(__('Type'))
                                    ->badge(),
                            ]),
                        TextEntry::make('description')
                            ->label(__('Description')),
                    ]),

                Section::make(__('Audit Information'))
                    ->description(__('Traceability of this record.'))
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('creator.name')
                                    ->label(__('Created By'))
                                    ->icon('heroicon-m-user')
                                    ->placeholder(__('System')),
                                TextEntry::make('created_at')
                                    ->label(__('Created At'))
                                    ->dateTime(),
                                TextEntry::make('editor.name')
                                    ->label(__('Updated By'))
                                    ->icon('heroicon-m-user-circle')
                                    ->placeholder(__('System')),
                                TextEntry::make('updated_at')
                                    ->label(__('Updated At'))
                                    ->dateTime(),
                            ]),
                    ])->collapsible(),
            ]);
    }
}
