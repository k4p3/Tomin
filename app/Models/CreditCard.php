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

class CreditCard extends Model
{
    use HasUlids, BelongsToWallet, HasAudit;

    protected $fillable = [
        'wallet_id', 'account_id', 'limit', 'closing_day', 'due_day', 
        'is_visible_to_contributors', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'limit' => 'encrypted:decimal:2',
        'is_visible_to_contributors' => 'boolean',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
