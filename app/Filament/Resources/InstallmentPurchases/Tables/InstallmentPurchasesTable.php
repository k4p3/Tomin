<?php

namespace App\Filament\Resources\InstallmentPurchases\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;

class InstallmentPurchasesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaction.description')
                    ->label(__('Description'))
                    ->searchable(),

                TextColumn::make('transaction.amount')
                    ->label(__('Installment Amount'))
                    ->money('MXN', locale: fn() => auth()->user()->number_format === 'comma_dot' ? 'en_US' : 'es_ES'),

                ViewColumn::make('progress')
                    ->label(__('Progreso'))
                    ->view('filament.tables.columns.msi-progress')
                    ->grow(),

                TextColumn::make('day_of_month')
                    ->label(__('Día de Cobro'))
                    ->badge()
                    ->color('info')
                    ->prefix('Día '),

                TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->date('d/m/Y')
                    ->sortable(),
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
