<?php

namespace App\Filament\Resources\RecurringTransactions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class RecurringTransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('description')
                    ->label(__('Description'))
                    ->searchable(),

                TextColumn::make('amount')
                    ->label(__('Amount'))
                    ->money('MXN', locale: fn() => auth()->user()->number_format === 'comma_dot' ? 'en_US' : 'es_ES')
                    ->sortable(),

                TextColumn::make('day_of_month')
                    ->label(__('Día del Mes'))
                    ->badge()
                    ->color('info')
                    ->prefix(__('Día ')),

                IconColumn::make('type')
                    ->label(__('Type'))
                    ->options([
                        'heroicon-o-arrow-trending-down' => 'expense',
                        'heroicon-o-arrow-trending-up' => 'income',
                    ])
                    ->colors([
                        'danger' => 'expense',
                        'success' => 'income',
                    ]),

                ToggleColumn::make('is_active')
                    ->label(__('Active')),

                TextColumn::make('last_processed_at')
                    ->label(__('Last Run'))
                    ->date('d/m/Y')
                    ->placeholder(__('Never')),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
