<?php

/**
 * Copyright (c) 2026 Jonathan
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */


namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Transaction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Facades\Filament;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;

class BudgetAnalysis extends BaseWidget
{
    protected static ?string $heading = 'Análisis de Presupuestos (Mes Actual)';

    protected static ?int $sort = 11;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $user = auth()->user();
        $numberLocale = ($user->number_format === 'comma_dot') ? 'en_US' : 'es_ES';

        return $table
            ->query(
                Category::query()
                    ->where('wallet_id', Filament::getTenant()->id)
                    ->where('monthly_budget', '>', 0)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Category'))
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('monthly_budget')
                    ->label(__('Budget'))
                    ->formatStateUsing(fn ($state) => Number::currency((float) $state, 'MXN', $numberLocale)),

                Tables\Columns\TextColumn::make('actual_spent')
                    ->label(__('Spent'))
                    ->getStateUsing(function ($record) {
                        return Transaction::where('category_id', $record->id)
                            ->where('type', 'expense')
                            ->whereMonth('transaction_date', Carbon::now()->month)
                            ->whereYear('transaction_date', Carbon::now()->year)
                            ->get()
                            ->sum(fn($t) => (float) $t->amount);
                    })
                    ->formatStateUsing(fn ($state) => Number::currency((float) $state, 'MXN', $numberLocale)),

                Tables\Columns\ViewColumn::make('usage')
                    ->label(__('Progress'))
                    ->view('filament.tables.columns.budget-progress'),

                Tables\Columns\TextColumn::make('remaining')
                    ->label(__('Remaining'))
                    ->getStateUsing(function ($record) {
                        $spent = Transaction::where('category_id', $record->id)
                            ->where('type', 'expense')
                            ->whereMonth('transaction_date', Carbon::now()->month)
                            ->whereYear('transaction_date', Carbon::now()->year)
                            ->get()
                            ->sum(fn($t) => (float) $t->amount);
                        
                        return (float) $record->monthly_budget - $spent;
                    })
                    ->formatStateUsing(fn ($state) => Number::currency((float) $state, 'MXN', $numberLocale))
                    ->color(fn ($state) => $state < 0 ? 'danger' : 'success'),
            ]);
    }
}
