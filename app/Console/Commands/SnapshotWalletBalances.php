<?php

/**
 * Copyright (c) 2026 Jonathan
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */


namespace App\Console\Commands;

use App\Models\Account;
use App\Models\BalanceHistory;
use App\Models\Wallet;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SnapshotWalletBalances extends Command
{
    protected $signature = 'wallets:snapshot';
    protected $description = 'Toma una captura del saldo total de cada billetera para el historial de patrimonio neto.';

    public function handle(): void
    {
        $today = Carbon::today();
        $wallets = Wallet::all();

        foreach ($wallets as $wallet) {
            // Calculamos el saldo total sumando todas las cuentas de esta billetera
            // Bypass del Global Scope si es necesario (aquí Wallet::all() nos da todas)
            $totalBalance = Account::withoutGlobalScopes()
                ->where('wallet_id', $wallet->id)
                ->get()
                ->sum(fn ($account) => (float) $account->balance);

            BalanceHistory::updateOrCreate(
                ['wallet_id' => $wallet->id, 'snapshot_date' => $today],
                ['total_balance' => $totalBalance]
            );
        }

        $this->info("Capturas de saldo completadas para " . $wallets->count() . " billeteras.");
    }
}
