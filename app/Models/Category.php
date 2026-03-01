<?php

namespace App\Models;

use App\Traits\BelongsToWallet;
use App\Traits\HasAudit;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasUlids, BelongsToWallet, HasAudit;

    protected $fillable = ['wallet_id', 'name', 'icon', 'color', 'monthly_budget', 'created_by', 'updated_by'];

    public function merchants(): HasMany
    {
        return $this->hasMany(Merchant::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
