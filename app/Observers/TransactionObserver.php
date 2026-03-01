<?php

namespace App\Observers;

use App\Models\Transaction;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class TransactionObserver
{
    /**
     * Se ejecuta después de que se crea una transacción.
     */
    public function created(Transaction $transaction): void
    {
        // 1. Actualizar el saldo de la cuenta
        $this->updateAccountBalance($transaction);

        // 2. Alerta de Gasto Alto
        if ($transaction->type === 'expense' && (float) $transaction->amount >= 5000) {
            $this->sendHighExpenseNotification($transaction);
        }

        // 3. Alerta de Bajo Saldo
        $this->checkLowBalance($transaction);
    }

    /**
     * Actualiza el saldo de la cuenta basado en la transacción.
     */
    protected function updateAccountBalance(Transaction $transaction): void
    {
        $account = $transaction->account;
        $amount = (float) $transaction->amount;

        if ($transaction->type === 'expense' || $transaction->type === 'transfer') {
            $account->balance = (float) $account->balance - $amount;
        } elseif ($transaction->type === 'income') {
            $account->balance = (float) $account->balance + $amount;
        }

        $account->save();
    }

    /**
     * Envía notificación de gasto alto.
     */
    protected function sendHighExpenseNotification(Transaction $transaction): void
    {
        if ($user = $transaction->user) {
            Notification::make()
                ->title(__('High Expense Alert'))
                ->warning()
                ->body(__('A high expense of :amount has been recorded in :account.', [
                    'amount' => number_format((float) $transaction->amount, 2),
                    'account' => $transaction->account->name,
                ]))
                ->actions([
                    Action::make('view')->label(__('View'))->url("/admin/transactions/{$transaction->id}")
                ])
                ->sendToDatabase($user);
        }
    }

    /**
     * Revisa si el saldo de la cuenta ha caído por debajo del umbral.
     */
    protected function checkLowBalance(Transaction $transaction): void
    {
        $account = $transaction->account;

        if ($account->low_balance_threshold !== null) {
            $currentBalance = (float) $account->balance;

            if ($currentBalance < (float) $account->low_balance_threshold) {
                $user = $transaction->user;

                Notification::make()
                    ->title(__('Low Balance Alert'))
                    ->danger()
                    ->body(__('The balance of :account has fallen below your threshold. Current balance: :balance', [
                        'account' => $account->name,
                        'balance' => number_format($currentBalance, 2),
                    ]))
                    ->actions([
                        Action::make('view_account')
                            ->label(__('View Account'))
                            ->url("/admin/accounts/{$account->id}/edit")
                    ])
                    ->sendToDatabase($user);
            }
        }
    }
}
