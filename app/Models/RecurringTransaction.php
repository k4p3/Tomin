<?php

/**
 * Copyright (c) 2026 Jonathan
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */


namespace App\Models;

use App\Traits\BelongsToWallet;
use App\Traits\HasAudit;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecurringTransaction extends Model
{
    use HasUlids, BelongsToWallet, HasAudit;

    protected $fillable = [
        'wallet_id', 'account_id', 'category_id', 'merchant_id', 'user_id',
        'amount', 'type', 'description', 'day_of_month', 'last_processed_at',
        'is_active', 'is_shared', 'created_by', 'updated_by'
    ];

    /**
     * Encriptar campos sensibles.
     */
    protected $casts = [
        'amount' => 'encrypted:decimal:2',
        'description' => 'encrypted',
        'is_active' => 'boolean',
        'is_shared' => 'boolean',
        'last_processed_at' => 'date',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }
}
