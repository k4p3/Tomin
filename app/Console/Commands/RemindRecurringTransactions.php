<?php

/**
 * Copyright (c) 2026 Jonathan Bailon Segura <jonn59@gmail.com>
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */




namespace App\Console\Commands;

use App\Models\RecurringTransaction;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Filament\Notifications\Notification;

class RemindRecurringTransactions extends Command
{
    protected $signature = 'transactions:remind-recurring';
    protected $description = 'Envía recordatorios de transacciones que se generarán mañana.';

    public function handle(): void
    {
        $tomorrow = Carbon::tomorrow();
        $dayOfMonth = $tomorrow->day;

        $reminders = RecurringTransaction::where('is_active', true)
            ->where('day_of_month', $dayOfMonth)
            ->with('user')
            ->get();

        foreach ($reminders as $recurring) {
            if ($recurring->user) {
                Notification::make()
                    ->title(__('Upcoming Transaction Reminder'))
                    ->warning()
                    ->body(__('Tomorrow, the transaction ":desc" for :amount will be automatically recorded.', [
                        'desc' => $recurring->description,
                        'amount' => number_format((float) $recurring->amount, 2),
                    ]))
                    ->sendToDatabase($recurring->user);
            }
        }

        $this->info("Enviados " . $reminders->count() . " recordatorios.");
    }
}
