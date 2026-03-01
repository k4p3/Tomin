<?php

/**
 * Copyright (c) 2026 Jonathan Bailon Segura <jonn59@gmail.com>
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */




namespace App\Filament\Widgets;

use App\Models\RecurringTransaction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Carbon;
use Filament\Facades\Filament;

class UpcomingAutomations extends BaseWidget
{
    protected static ?int $sort = 7;

    protected static ?string $heading = 'Próximas Automatizaciones (7 días)';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $today = Carbon::today()->day;
        $nextWeek = Carbon::today()->addDays(7)->day;

        return $table
            ->query(
                RecurringTransaction::query()
                    ->where('is_active', true)
                    ->where('wallet_id', Filament::getTenant()->id)
                    // Lógica para manejar el cambio de mes (ej. si hoy es 28 y la recurrencia es el 2)
                    ->where(function ($query) use ($today, $nextWeek) {
                        if ($nextWeek > $today) {
                            $query->whereBetween('day_of_month', [$today + 1, $nextWeek]);
                        } else {
                            // Estamos al final del mes, buscamos lo que queda de este y el inicio del próximo
                            $query->where('day_of_month', '>', $today)
                                  ->orWhere('day_of_month', '<=', $nextWeek);
                        }
                    })
            )
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label(__('Description')),
                
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('Amount'))
                    ->money('MXN', locale: fn() => auth()->user()->number_format === 'comma_dot' ? 'en_US' : 'es_ES'),

                Tables\Columns\TextColumn::make('day_of_month')
                    ->label(__('Day of Month'))
                    ->badge()
                    ->color('info')
                    ->prefix(__('Día ')),

                Tables\Columns\TextColumn::make('account.name')
                    ->label(__('Account')),

                Tables\Columns\IconColumn::make('type')
                    ->label(__('Type'))
                    ->options([
                        'heroicon-o-arrow-trending-down' => 'expense',
                        'heroicon-o-arrow-trending-up' => 'income',
                    ])
                    ->colors([
                        'danger' => 'expense',
                        'success' => 'income',
                    ]),
            ]);
    }
}
