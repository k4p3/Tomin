<?php

/**
 * Copyright (c) 2026 Jonathan Bailon Segura <jonn59@gmail.com>
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */




namespace App\Models;

use App\Traits\BelongsToWallet;
use App\Traits\HasAudit;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstallmentPurchase extends Model
{
    use HasUlids, BelongsToWallet, HasAudit;

    protected $fillable = [
        'wallet_id', 'transaction_id', 'total_installments', 'remaining_installments', 'day_of_month',
        'created_by', 'updated_by'
    ];

    /**
     * Relación con la transacción original del primer abono.
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
