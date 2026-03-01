<?php

namespace App\Filament\Widgets;

use App\Models\BalanceHistory;
use Filament\Widgets\ChartWidget;
use Filament\Facades\Filament;
use Illuminate\Support\Carbon;

class NetWorthChart extends ChartWidget
{
    protected ?string $heading = 'Evolución del Patrimonio Neto (30 días)';

    protected static ?int $sort = 10;

    protected int | string | array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $wallet = Filament::getTenant();
        
        // Obtener historial de los últimos 30 días
        $history = BalanceHistory::where('wallet_id', $wallet->id)
            ->where('snapshot_date', '>=', now()->subDays(30))
            ->orderBy('snapshot_date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => __('Net Worth'),
                    'data' => $history->pluck('total_balance')->toArray(),
                    'fill' => 'start',
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                ],
            ],
            'labels' => $history->map(fn($h) => Carbon::parse($h->snapshot_date)->format('d M'))->toArray(),
        ];
    }
}
