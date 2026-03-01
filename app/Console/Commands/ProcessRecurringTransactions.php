<?php

/**
 * Copyright (c) 2026 Jonathan Bailon Segura <jonn59@gmail.com>
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */




namespace App\Console\Commands;

use App\Models\RecurringTransaction;
use App\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Filament\Notifications\Notification;
use Throwable;

class ProcessRecurringTransactions extends Command
{
    protected $signature = 'transactions:process-recurring';
    protected $description = 'Procesa las transacciones recurrentes mensuales que corresponden al día de hoy.';

    public function handle(): void
    {
        $today = Carbon::today();
        $dayOfMonth = $today->day;

        $recurringToProcess = RecurringTransaction::where('is_active', true)
            ->where('day_of_month', $dayOfMonth)
            ->where(function ($query) use ($today) {
                $query->whereNull('last_processed_at')
                      ->orWhereMonth('last_processed_at', '!=', $today->month)
                      ->orWhereYear('last_processed_at', '!=', $today->year);
            })
            ->with(['user', 'account'])
            ->get();

        $count = 0;
        foreach ($recurringToProcess as $recurring) {
            try {
                // Validación de integridad rápida
                if (!$recurring->account) {
                    throw new \Exception("Linked account not found for recurring transaction: {$recurring->description}");
                }

                Transaction::create([
                    'wallet_id' => $recurring->wallet_id,
                    'account_id' => $recurring->account_id,
                    'category_id' => $recurring->category_id,
                    'merchant_id' => $recurring->merchant_id,
                    'user_id' => $recurring->user_id,
                    'amount' => $recurring->amount,
                    'type' => $recurring->type,
                    'description' => $recurring->description . ' (' . __('Recurring') . ')',
                    'transaction_date' => $today,
                    'is_shared' => $recurring->is_shared,
                    'is_automated' => true,
                ]);

                $recurring->update(['last_processed_at' => $today]);

                if ($recurring->user) {
                    Notification::make()
                        ->title(__('Recurring Transaction Processed'))
                        ->success()
                        ->body(__(':desc for :amount has been automatically recorded.', [
                            'desc' => $recurring->description,
                            'amount' => number_format((float) $recurring->amount, 2),
                        ]))
                        ->sendToDatabase($recurring->user);
                }

                $count++;
            } catch (Throwable $e) {
                $this->error("Error processing ID {$recurring->id}: " . $e->getMessage());
                
                if ($recurring->user) {
                    Notification::make()
                        ->title(__('Automation Error'))
                        ->danger()
                        ->body(__('Failed to process recurring transaction: :desc. Please check the linked account.', [
                            'desc' => $recurring->description,
                        ]))
                        ->sendToDatabase($recurring->user);
                }
            }
        }

        $this->info("Procesadas $count transacciones recurrentes con éxito.");
    }
}
