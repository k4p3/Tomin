<?php

/**
 * Copyright (c) 2026 Jonathan Bailon Segura <jonn59@gmail.com>
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */




namespace App\Filament\Widgets;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\InstallmentPurchase;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Facades\Filament;
use Carbon\Carbon;
use Illuminate\Support\Number;

class WalletOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected ?string $pollingInterval = '30s';

    /**
     * Generar las estadísticas para la billetera actual.
     */
    protected function getStats(): array
    {
        $user = auth()->user();
        $numberLocale = ($user->number_format === 'comma_dot') ? 'en_US' : 'es_ES';

        // 1. Calcular Balance Total
        $accounts = Account::all();
        $totalBalance = $accounts->sum(fn ($account) => (float) $account->balance);

        // 2. Gastos del Mes Actual
        $monthlyExpenses = Transaction::where('type', 'expense')
            ->whereMonth('transaction_date', Carbon::now()->month)
            ->whereYear('transaction_date', Carbon::now()->year)
            ->get()
            ->sum(fn ($transaction) => (float) $transaction->amount);

        // 3. Deuda Pendiente MSI
        $pendingMSI = InstallmentPurchase::where('remaining_installments', '>', 0)
            ->with('transaction')
            ->get()
            ->sum(fn ($ip) => (float) $ip->transaction->amount * $ip->remaining_installments);

        // Obtener la moneda de la billetera actual
        $currency = Filament::getTenant()?->currency ?? 'MXN';

        return [
            Stat::make(__('Balance Total'), Number::format($totalBalance, precision: 2, locale: $numberLocale) . ' ' . $currency)
                ->description(__('Suma de todas tus cuentas'))
                ->descriptionIcon('heroicon-m-wallet')
                ->color('primary'),

            Stat::make(__('Gastos del Mes'), Number::format($monthlyExpenses, precision: 2, locale: $numberLocale) . ' ' . $currency)
                ->description(__('Salidas registradas este mes'))
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),

            Stat::make(__('Deuda MSI'), Number::format($pendingMSI, precision: 2, locale: $numberLocale) . ' ' . $currency)
                ->description(__('Total de pagos futuros programados'))
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('warning'),
        ];
    }
}
