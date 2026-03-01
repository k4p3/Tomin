<?php

/**
 * Copyright (c) 2026 Jonathan
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */


namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ActualVsAutomatedChart extends ChartWidget
{
    protected ?string $heading = 'Gasto Manual vs Automatizado (Mes Actual)';

    protected static ?int $sort = 9;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        // 1. Obtener gastos del mes (el Scope ya filtra por Wallet)
        $transactions = Transaction::where('type', 'expense')
            ->whereMonth('transaction_date', Carbon::now()->month)
            ->whereYear('transaction_date', Carbon::now()->year)
            ->get();

        // 2. Sumar montos (desencriptados) según el flag is_automated
        $automatedTotal = (float) $transactions->where('is_automated', true)->sum(fn ($t) => (float) $t->amount);
        $manualTotal = (float) $transactions->where('is_automated', false)->sum(fn ($t) => (float) $t->amount);

        return [
            'datasets' => [
                [
                    'label' => 'Monto Gastado',
                    'data' => [$manualTotal, $automatedTotal],
                    'backgroundColor' => [
                        '#10b981', // Verde para Manual
                        '#6366f1', // Indigo para Automatizado
                    ],
                ],
            ],
            'labels' => ['Manual', 'Automatizado'],
        ];
    }
}
