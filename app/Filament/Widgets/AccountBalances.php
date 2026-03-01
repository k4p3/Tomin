<?php

/**
 * Copyright (c) 2026 Jonathan
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */


namespace App\Filament\Widgets;

use App\Models\Account;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Facades\Filament;
use UnitEnum;

class AccountBalances extends BaseWidget
{
    protected static ?int $sort = 6;

    protected static ?string $heading = 'Mis Saldos Actuales';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Account::query()->where('wallet_id', Filament::getTenant()->id)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label(__('Type'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => __($state)),

                Tables\Columns\TextColumn::make('balance')
                    ->label(__('Balance'))
                    ->money('MXN', locale: fn() => auth()->user()->number_format === 'comma_dot' ? 'en_US' : 'es_ES')
                    ->color(fn ($record) => 
                        ($record->low_balance_threshold !== null && (float) $record->balance < (float) $record->low_balance_threshold) 
                            ? 'danger' 
                            : 'success'
                    )
                    ->weight('bold'),

                Tables\Columns\IconColumn::make('status')
                    ->label(__('Alert'))
                    ->getStateUsing(fn ($record) => 
                        $record->low_balance_threshold !== null && (float) $record->balance < (float) $record->low_balance_threshold
                    )
                    ->boolean()
                    ->trueIcon('heroicon-o-exclamation-triangle')
                    ->falseIcon('heroicon-o-check-circle')
                    ->trueColor('danger')
                    ->falseColor('success'),
            ]);
    }
}
