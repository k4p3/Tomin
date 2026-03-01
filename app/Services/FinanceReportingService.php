<?php

/**
 * Copyright (c) 2026 Jonathan
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */


namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Carbon;

class FinanceReportingService
{
    /**
     * Obtiene un resumen financiero con comparativa.
     */
    public function getMonthlySummary(Carbon $date, string $walletId): array
    {
        $currentMonth = $this->getTotals($date, $walletId);
        $previousMonth = $this->getTotals($date->copy()->subMonth(), $walletId);

        return [
            'current' => $currentMonth,
            'comparison' => [
                'income_diff' => $currentMonth['total_income'] - $previousMonth['total_income'],
                'expense_diff' => $currentMonth['total_expenses'] - $previousMonth['total_expenses'],
                'income_percentage' => $this->calculatePercentage($currentMonth['total_income'], $previousMonth['total_income']),
                'expense_percentage' => $this->calculatePercentage($currentMonth['total_expenses'], $previousMonth['total_expenses']),
            ],
            'expenses_by_user' => $currentMonth['expenses_by_user'],
        ];
    }

    private function getTotals(Carbon $date, string $walletId): array
    {
        $transactions = Transaction::where('wallet_id', $walletId)
            ->whereMonth('transaction_date', $date->month)
            ->whereYear('transaction_date', $date->year)
            ->get();

        $income = (float) $transactions->where('type', 'income')->sum(fn($t) => (float) $t->amount);
        $expenses = (float) $transactions->where('type', 'expense')->sum(fn($t) => (float) $t->amount);

        $byUser = $transactions->where('type', 'expense')
            ->groupBy('user_id')
            ->map(fn($group) => [
                'name' => $group->first()->user->name ?? 'Unknown',
                'amount' => (float) $group->sum(fn($t) => (float) $t->amount)
            ]);

        return [
            'total_income' => $income,
            'total_expenses' => $expenses,
            'net_flow' => $income - $expenses,
            'expenses_by_user' => $byUser,
        ];
    }

    private function calculatePercentage(float $current, float $previous): float
    {
        if ($previous == 0) return $current > 0 ? 100 : 0;
        return (($current - $previous) / $previous) * 100;
    }
}
