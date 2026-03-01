<?php

/**
 * Copyright (c) 2026 Jonathan
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */


namespace App\Observers;

use App\Models\Account;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class AccountObserver
{
    /**
     * Se ejecuta cuando se actualiza una cuenta.
     */
    public function updated(Account $account): void
    {
        // Solo registramos si cambió el umbral de bajo saldo
        if ($account->wasChanged('low_balance_threshold')) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'account_limit_changed',
                'model_type' => Account::class,
                'model_id' => $account->id,
                'old_values' => ['low_balance_threshold' => $account->getOriginal('low_balance_threshold')],
                'new_values' => ['low_balance_threshold' => $account->low_balance_threshold],
                'ip_address' => request()->ip(),
            ]);
        }
    }
}
