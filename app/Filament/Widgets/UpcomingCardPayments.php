<?php

namespace App\Filament\Widgets;

use App\Models\CreditCard;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Carbon;

class UpcomingCardPayments extends BaseWidget
{
    protected static ?int $sort = 8;

    protected static ?string $heading = 'Próximos Vencimientos de Tarjetas (Mes Actual)';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Especificamos la tabla base para evitar ambigüedades en el Global Scope
                CreditCard::query()
                    ->from('credit_cards')
                    ->join('accounts', 'credit_cards.account_id', '=', 'accounts.id')
                    ->where('accounts.wallet_id', \Filament\Facades\Filament::getTenant()->id)
                    ->select('credit_cards.*', 'accounts.name as account_name')
            )
            ->columns([
                Tables\Columns\TextColumn::make('account_name')
                    ->label(__('Name'))
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('limit')
                    ->label(__('Credit Limit'))
                    ->money('MXN', locale: fn() => auth()->user()->number_format === 'comma_dot' ? 'en_US' : 'es_ES'),

                Tables\Columns\TextColumn::make('closing_day')
                    ->label(__('Closing Day'))
                    ->badge()
                    ->color('info')
                    ->prefix(__('Día ')),

                Tables\Columns\TextColumn::make('due_day')
                    ->label(__('Due Day'))
                    ->badge()
                    ->color('danger')
                    ->prefix(__('Día ')),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('Status'))
                    ->getStateUsing(fn ($record) => 
                        (Carbon::today()->day > $record->due_day) ? __('Vencido') : __('Pendiente')
                    )
                    ->badge()
                    ->color(fn ($state) => $state === __('Vencido') ? 'danger' : 'warning'),
            ]);
    }
}
