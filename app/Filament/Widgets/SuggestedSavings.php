<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Facades\Filament;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;

class SuggestedSavings extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $user = auth()->user();
        $numberLocale = ($user->number_format === 'comma_dot') ? 'en_US' : 'es_ES';
        $wallet = Filament::getTenant();
        $today = Carbon::today();

        // 1. Obtener flujo neto del mes
        $transactions = Transaction::where('wallet_id', $wallet->id)
            ->whereMonth('transaction_date', $today->month)
            ->whereYear('transaction_date', $today->year)
            ->get();

        $income = (float) $transactions->where('type', 'income')->sum(fn($t) => (float) $t->amount);
        $expenses = (float) $transactions->where('type', 'expense')->sum(fn($t) => (float) $t->amount);
        $netFlow = $income - $expenses;

        // 2. Cálculo de ahorro sugerido (20% del flujo si es positivo)
        $suggestion = $netFlow > 0 ? $netFlow * 0.20 : 0;

        return [
            Stat::make(__('Suggested Savings'), Number::format($suggestion, precision: 2, locale: $numberLocale) . ' ' . $wallet->currency)
                ->description(__('Based on 20% of your current net flow'))
                ->descriptionIcon('heroicon-m-sparkles')
                ->color($suggestion > 0 ? 'success' : 'gray'),
            
            Stat::make(__('Net Flow'), Number::format($netFlow, precision: 2, locale: $numberLocale) . ' ' . $wallet->currency)
                ->description(__('Income minus Expenses this month'))
                ->color($netFlow >= 0 ? 'success' : 'danger'),
        ];
    }
}
