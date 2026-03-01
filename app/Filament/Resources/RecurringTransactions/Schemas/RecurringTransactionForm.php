<?php

/**
 * Copyright (c) 2026 Jonathan Bailon Segura <jonn59@gmail.com>
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */




namespace App\Filament\Resources\RecurringTransactions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class RecurringTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Automation Details'))
                    ->description(__('Configure how and when this transaction will be generated.'))
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('account_id')
                                    ->label(__('Account'))
                                    ->relationship('account', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                
                                Select::make('type')
                                    ->label(__('Type'))
                                    ->options([
                                        'expense' => __('Expense'),
                                        'income' => __('Income'),
                                    ])
                                    ->default('expense')
                                    ->required()
                                    ->native(false),

                                TextInput::make('day_of_month')
                                    ->label(__('Day of Month'))
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(31)
                                    ->default(1)
                                    ->required(),
                            ]),
                    ]),

                Section::make(__('Transaction Info'))
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('amount')
                                    ->label(__('Amount'))
                                    ->numeric()
                                    ->prefix('$')
                                    ->required(),

                                Select::make('category_id')
                                    ->label(__('Category'))
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload(),

                                Select::make('merchant_id')
                                    ->label(__('Merchant'))
                                    ->relationship('merchant', 'name')
                                    ->searchable()
                                    ->preload(),

                                TextInput::make('description')
                                    ->label(__('Description'))
                                    ->required()
                                    ->maxLength(255),
                            ]),
                    ]),

                Section::make(__('Status & Privacy'))
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('is_active')
                                    ->label(__('Active'))
                                    ->default(true),
                                
                                Toggle::make('is_shared')
                                    ->label(__('Shared with Wallet'))
                                    ->default(true),
                            ]),
                    ]),
            ]);
    }
}
