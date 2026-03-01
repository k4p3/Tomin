<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ExpensesByCategoryChart extends ChartWidget
{
    protected ?string $heading = 'Gastos por Categoría (Mes Actual)';

    protected static ?int $sort = 4;

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getData(): array
    {
        // 1. Obtener todas las transacciones del mes
        $transactions = Transaction::where('type', 'expense')
            ->whereMonth('transaction_date', Carbon::now()->month)
            ->whereYear('transaction_date', Carbon::now()->year)
            ->with('category')
            ->get();

        // 2. Agrupar por nombre de categoría y sumar montos (desencriptados)
        $data = $transactions->groupBy(fn ($t) => $t->category?->name ?? 'Sin Categoría')
            ->map(fn ($group) => (float) $group->sum(fn ($t) => (float) $t->amount));

        return [
            'datasets' => [
                [
                    'label' => 'Monto Gastado',
                    'data' => $data->values()->toArray(),
                    'backgroundColor' => [
                        '#ef4444', '#f97316', '#f59e0b', '#10b981', '#3b82f6', 
                        '#6366f1', '#8b5cf6', '#d946ef', '#ec4899', '#f43f5e'
                    ],
                ],
            ],
            'labels' => $data->keys()->toArray(),
        ];
    }
}
