<?php

/**
 * Copyright (c) 2026 Jonathan Bailon Segura <jonn59@gmail.com>
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */




namespace App\Filament\Resources\Transactions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Number;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaction_date')
                    ->label(__('Date'))
                    ->date('d/m/Y')
                    ->sortable(),
                
                TextColumn::make('account.name')
                    ->label(__('Account'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label(__('Type'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'income' => 'success',
                        'expense' => 'danger',
                        'transfer' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'income' => __('Income'),
                        'expense' => __('Expense'),
                        'transfer' => __('Transfer'),
                        default => $state,
                    }),

                TextColumn::make('amount')
                    ->label(__('Amount'))
                    ->sortable()
                    ->formatStateUsing(function ($state, $record) {
                        // 1. Verificación de Privacidad
                        if (!$record->is_shared && Auth::id() !== $record->user_id) {
                            return '***';
                        }

                        // 2. Aplicar Formato de Moneda Manualmente
                        $user = auth()->user();
                        $numberLocale = ($user->number_format === 'comma_dot') ? 'en_US' : 'es_ES';
                        
                        return Number::currency((float) $state, 'MXN', $numberLocale);
                    }),

                TextColumn::make('description')
                    ->label(__('Description'))
                    ->searchable()
                    ->limit(30)
                    ->formatStateUsing(function ($state, $record) {
                        if (!$record->is_shared && Auth::id() !== $record->user_id) {
                            return __('*** PRIVATE ***');
                        }
                        return $state;
                    }),

                TextColumn::make('category.name')
                    ->label(__('Category'))
                    ->placeholder(__('No Category'))
                    ->searchable()
                    ->hidden(fn ($record) => $record && !$record->is_shared && Auth::id() !== $record->user_id),

                IconColumn::make('is_shared')
                    ->label(__('Shared'))
                    ->boolean()
                    ->trueIcon('heroicon-o-users')
                    ->falseIcon('heroicon-o-lock-closed')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label(__('Type'))
                    ->options([
                        'income' => __('Income'),
                        'expense' => __('Expense'),
                        'transfer' => __('Transfer'),
                    ]),
                
                SelectFilter::make('category_id')
                    ->label(__('Category'))
                    ->relationship('category', 'name'),

                Filter::make('transaction_date')
                    ->form([
                        DatePicker::make('from')->label(__('From')),
                        DatePicker::make('until')->label(__('Until')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn (Builder $query, $date): Builder => $query->whereDate('transaction_date', '>=', $date))
                            ->when($data['until'], fn (Builder $query, $date): Builder => $query->whereDate('transaction_date', '<=', $date));
                    })
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                    ->visible(fn ($record) => $record->is_shared || Auth::id() === $record->user_id),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
