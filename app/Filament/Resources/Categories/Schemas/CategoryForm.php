<?php

/**
 * Copyright (c) 2026 Jonathan
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */


namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('Name'))
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('monthly_budget')
                    ->label(__('Monthly Budget'))
                    ->helperText(__('Set to 0 for no limit.'))
                    ->numeric()
                    ->prefix('$')
                    ->default(0)
                    ->required(),

                TextInput::make('icon')
                    ->label(__('Icon'))
                    ->placeholder('heroicon-o-home'),
                
                ColorPicker::make('color')
                    ->label(__('Color')),
            ]);
    }
}
