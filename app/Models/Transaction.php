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

class Transaction extends Model
{
    use HasUlids, BelongsToWallet, HasAudit;

    protected $fillable = [
        'wallet_id', 'account_id', 'category_id', 'merchant_id', 'user_id', 
        'amount', 'type', 'is_shared', 'is_automated', 'description', 'transaction_date',
        'transfer_id', 'created_by', 'updated_by'
    ];

    /**
     * Encriptar campos sensibles.
     */
    protected $casts = [
        'amount' => 'encrypted:decimal:2',
        'description' => 'encrypted',
        'is_shared' => 'boolean',
        'is_automated' => 'boolean',
        'transaction_date' => 'date',
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
