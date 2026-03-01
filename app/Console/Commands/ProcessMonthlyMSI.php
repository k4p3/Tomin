<?php

/**
 * Copyright (c) 2026 Jonathan
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */


namespace App\Console\Commands;

use App\Models\InstallmentPurchase;
use App\Models\Transaction;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Filament\Notifications\Notification;

class ProcessMonthlyMSI extends Command
{
    /**
     * El nombre y la firma del comando de consola.
     */
    protected $signature = 'msi:process';

    /**
     * Descripción del comando.
     */
    protected $description = 'Procesa las mensualidades de MSI que corresponden al día de hoy y notifica a los usuarios.';

    /**
     * Ejecuta el comando de consola.
     */
    public function handle(): void
    {
        $todayDay = Carbon::today()->day;
        
        // Buscamos compras MSI que deban cargarse hoy y tengan mensualidades pendientes
        $installmentsToCharge = InstallmentPurchase::where('day_of_month', $todayDay)
            ->where('remaining_installments', '>', 0)
            ->with('transaction.user')
            ->get();

        $count = 0;
        foreach ($installmentsToCharge as $installment) {
            $baseTransaction = $installment->transaction;
            $currentInstallmentNum = $installment->total_installments - $installment->remaining_installments + 1;

            // 1. Generar la nueva transacción de gasto
            $newTransaction = Transaction::create([
                'wallet_id' => $baseTransaction->wallet_id,
                'account_id' => $baseTransaction->account_id,
                'category_id' => $baseTransaction->category_id,
                'merchant_id' => $baseTransaction->merchant_id,
                'user_id' => $baseTransaction->user_id,
                'amount' => $baseTransaction->amount,
                'type' => 'expense',
                'description' => str_replace('Mensualidad 1/', "Mensualidad $currentInstallmentNum/", $baseTransaction->description),
                'transaction_date' => Carbon::today(),
                'is_shared' => $baseTransaction->is_shared,
                'is_automated' => true,
            ]);

            // 2. Descontar una mensualidad pendiente
            $installment->decrement('remaining_installments');

            // 3. NOTIFICAR al autor de la compra
            if ($baseTransaction->user) {
                Notification::make()
                    ->title(__('MSI Payment Processed'))
                    ->info()
                    ->body(__('The installment :num/:total for ":desc" has been recorded.', [
                        'num' => $currentInstallmentNum,
                        'total' => $installment->total_installments,
                        'desc' => $baseTransaction->description,
                    ]))
                    ->sendToDatabase($baseTransaction->user);
            }

            $count++;
        }

        $this->info("Procesadas $count mensualidades de MSI con éxito.");
    }
}
