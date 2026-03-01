<?php

/**
 * Copyright (c) 2026 Jonathan
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */


namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Transaction Details'))
                    ->description(__('Enter the basic information about this movement.'))
                    ->schema([
                        Grid::make(2)
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
                                        'transfer' => __('Transfer'),
                                    ])
                                    ->default('expense')
                                    ->required()
                                    ->native(false),

                                TextInput::make('amount')
                                    ->label(__('Amount'))
                                    ->numeric()
                                    ->prefix('$')
                                    ->required(),

                                DatePicker::make('transaction_date')
                                    ->label(__('Date'))
                                    ->default(now())
                                    ->required(),
                            ]),
                    ]),

                Section::make(__('Categorization & Notes'))
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('category_id')
                                    ->label(__('Category'))
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload(),

                                Select::make('merchant_id')
                                    ->label(__('Merchant'))
                                    ->relationship('merchant', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->required(),
                                    ]),

                                TextInput::make('description')
                                    ->label(__('Description'))
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                            ]),
                    ]),

                Section::make(__('Privacy'))
                    ->schema([
                        Toggle::make('is_shared')
                            ->label(__('Shared with Wallet'))
                            ->helperText(__('If disabled, sensitive details will only be visible to you.'))
                            ->default(true),
                    ]),

                Hidden::make('user_id')
                    ->default(auth()->id()),
            ]);
    }
}
