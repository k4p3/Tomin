<?php

/**
 * Copyright (c) 2026 Jonathan Bailon Segura <jonn59@gmail.com>
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */




namespace App\Filament\Resources\Wallets\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WalletForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('Name'))
                    ->placeholder(__('Ej. Gastos Personales, Ahorros'))
                    ->required()
                    ->maxLength(255),
                Select::make('currency')
                    ->label(__('Currency'))
                    ->options([
                        'MXN' => 'Peso Mexicano (MXN)',
                        'USD' => 'Dólar Americano (USD)',
                        'EUR' => 'Euro (EUR)',
                    ])
                    ->default('MXN')
                    ->required()
                    ->native(false),
            ]);
    }
}
