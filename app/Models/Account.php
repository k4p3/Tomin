<?php

namespace App\Models;

use App\Traits\BelongsToWallet;
use App\Traits\HasAudit;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Account extends Model
{
    use HasUlids, BelongsToWallet, HasAudit;

    protected $fillable = ['wallet_id', 'name', 'type', 'balance', 'low_balance_threshold', 'created_by', 'updated_by'];

    protected $casts = [
        'balance' => 'encrypted:decimal:2',
    ];

    public function creditCard(): HasOne
    {
        return $this->hasOne(CreditCard::class);
    }
}
