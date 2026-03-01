<?php

/**
 * Copyright (c) 2026 Jonathan Bailon Segura <jonn59@gmail.com>
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */




namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class IncomeVsExpensesChart extends ChartWidget
{
    protected ?string $heading = 'Ingresos vs Gastos (Últimos 6 meses)';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        // 1. Obtener transacciones de los últimos 6 meses
        $transactions = Transaction::where('transaction_date', '>=', now()->subMonths(6)->startOfMonth())
            ->get();

        $labels = [];
        $incomeData = [];
        $expenseData = [];

        // 2. Generar datos por mes
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->translatedFormat('M Y');

            // Filtrar ingresos del mes
            $incomeData[] = (float) $transactions
                ->where('type', 'income')
                ->filter(fn($t) => $t->transaction_date->month == $month->month && $t->transaction_date->year == $month->year)
                ->sum(fn ($t) => (float) $t->amount);

            // Filtrar gastos del mes
            $expenseData[] = (float) $transactions
                ->where('type', 'expense')
                ->filter(fn($t) => $t->transaction_date->month == $month->month && $t->transaction_date->year == $month->year)
                ->sum(fn ($t) => (float) $t->amount);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Ingresos',
                    'data' => $incomeData,
                    'borderColor' => '#10b981',
                    'fill' => false,
                ],
                [
                    'label' => 'Gastos',
                    'data' => $expenseData,
                    'borderColor' => '#ef4444',
                    'fill' => false,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
