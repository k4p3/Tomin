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

class SharedVsPersonalChart extends ChartWidget
{
    protected ?string $heading = 'Gasto Compartido vs Personal (Mes Actual)';

    protected static ?int $sort = 5;

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getData(): array
    {
        // 1. Obtener gastos del mes (el Scope ya filtra por Wallet)
        $transactions = Transaction::where('type', 'expense')
            ->whereMonth('transaction_date', Carbon::now()->month)
            ->whereYear('transaction_date', Carbon::now()->year)
            ->get();

        // 2. Agrupar por el flag is_shared y sumar montos (desencriptados)
        $sharedTotal = (float) $transactions->where('is_shared', true)->sum(fn ($t) => (float) $t->amount);
        $personalTotal = (float) $transactions->where('is_shared', false)->sum(fn ($t) => (float) $t->amount);

        return [
            'datasets' => [
                [
                    'label' => 'Monto Gastado',
                    'data' => [$sharedTotal, $personalTotal],
                    'backgroundColor' => [
                        '#3b82f6', // Azul para compartido (Users)
                        '#ef4444', // Rojo para personal (Lock)
                    ],
                ],
            ],
            'labels' => ['Compartido', 'Personal'],
        ];
    }
}
