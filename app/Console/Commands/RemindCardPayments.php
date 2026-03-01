<?php

/**
 * Copyright (c) 2026 Jonathan
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */


namespace App\Console\Commands;

use App\Models\CreditCard;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class RemindCardPayments extends Command
{
    protected $signature = 'cards:remind-payments';
    protected $description = 'Envía recordatorios para las tarjetas de crédito que vencen en 3 días.';

    public function handle(): void
    {
        $targetDate = Carbon::today()->addDays(3);
        $targetDay = $targetDate->day;

        $cardsToRemind = CreditCard::where('due_day', $targetDay)
            ->with(['account.wallet.users'])
            ->get();

        $count = 0;
        foreach ($cardsToRemind as $card) {
            $account = $card->account;
            $wallet = $account?->wallet;
            
            if (!$account || !$wallet) continue;

            $users = $wallet->users->filter(function ($user) use ($card) {
                if ($user->pivot->role === 'owner') return true;
                return $card->is_visible_to_contributors;
            });

            if ($users->isNotEmpty()) {
                Notification::make()
                    ->title(__('Credit Card Payment Reminder'))
                    ->warning()
                    ->body(__('The payment for ":account" is due in 3 days (:date).', [
                        'account' => $account->name,
                        'date' => $targetDate->translatedFormat('d F'),
                    ]))
                    ->actions([
                        Action::make('view_account')
                            ->label(__('View Account'))
                            ->url("/admin/accounts/{$account->id}/edit")
                    ])
                    ->sendToDatabase($users);
                
                $count++;
            }
        }

        $this->info("Enviados recordatorios para $count tarjetas de crédito.");
    }
}
