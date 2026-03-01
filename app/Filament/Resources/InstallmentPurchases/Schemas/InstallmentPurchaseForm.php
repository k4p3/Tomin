<?php

/**
 * Copyright (c) 2026 Jonathan Bailon Segura <jonn59@gmail.com>
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */




namespace App\Filament\Resources\InstallmentPurchases\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class InstallmentPurchaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('account_id')
                    ->label(__('Account'))
                    ->relationship(
                        'transaction.account', 
                        'name',
                        fn ($query) => $query->where('type', 'credit')
                    )
                    ->required()
                    ->searchable()
                    ->preload(),

                Select::make('merchant_id')
                    ->label(__('Merchant'))
                    ->relationship('transaction.merchant', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),

                TextInput::make('total_amount')
                    ->label(__('Amount'))
                    ->numeric()
                    ->prefix('$')
                    ->required()
                    ->live(),

                TextInput::make('total_installments')
                    ->label(__('MSI'))
                    ->numeric()
                    ->default(12)
                    ->required()
                    ->live(),

                TextInput::make('installment_amount')
                    ->label(__('Installment Amount'))
                    ->numeric()
                    ->prefix('$')
                    ->required()
                    ->live()
                    ->placeholder(fn ($get) => 
                        ($get('total_amount') && $get('total_installments')) 
                            ? number_format($get('total_amount') / $get('total_installments'), 2)
                            : '0.00'
                    ),

                DatePicker::make('transaction_date')
                    ->label(__('Date'))
                    ->default(now())
                    ->required(),

                Select::make('category_id')
                    ->label(__('Category'))
                    ->relationship('transaction.category', 'name')
                    ->searchable()
                    ->preload(),

                TextInput::make('description')
                    ->label(__('Description'))
                    ->required()
                    ->maxLength(255),

                Toggle::make('is_shared')
                    ->label(__('Shared'))
                    ->default(true),
            ]);
    }
}
