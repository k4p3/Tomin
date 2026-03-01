<?php

/**
 * Copyright (c) 2026 Jonathan
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */


namespace App\Filament\Resources\Accounts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class AccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Account Details'))
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('Name'))
                                    ->required()
                                    ->maxLength(255),
                                Select::make('type')
                                    ->label(__('Type'))
                                    ->options([
                                        'cash' => __('Cash'),
                                        'debit' => __('Debit'),
                                        'savings' => __('Savings'),
                                        'credit' => __('Credit Card'),
                                    ])
                                    ->required()
                                    ->live()
                                    ->native(false),
                            ]),
                    ]),

                // Sección dinámica para Tarjetas de Crédito
                Section::make(__('Credit Card Details'))
                    ->description(__('Configure your credit limit and payment dates.'))
                    ->relationship('creditCard') // Vinculamos los campos al modelo CreditCard
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('limit')
                                    ->label(__('Credit Limit'))
                                    ->numeric()
                                    ->prefix('$')
                                    ->required(),
                                TextInput::make('closing_day')
                                    ->label(__('Closing Day'))
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(31)
                                    ->required(),
                                TextInput::make('due_day')
                                    ->label(__('Due Day'))
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(31)
                                    ->required(),
                            ]),
                    ])
                    ->hidden(fn ($get) => $get('type') !== 'credit'),

                Section::make(__('Financial Limits'))
                    ->description(__('Set alerts for when your balance reaches a certain point.'))
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('balance')
                                    ->label(__('Current Balance / Debt'))
                                    ->helperText(__('For credit cards, enter your current debt as a negative number or zero.'))
                                    ->numeric()
                                    ->prefix('$')
                                    ->required(),
                                TextInput::make('low_balance_threshold')
                                    ->label(__('Low Balance Threshold'))
                                    ->helperText(__('A notification will be sent when the balance falls below this amount.'))
                                    ->numeric()
                                    ->prefix('$')
                                    ->placeholder('0.00'),
                            ]),
                    ]),
            ]);
    }
}
