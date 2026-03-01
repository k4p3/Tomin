<?php

/**
 * Copyright (c) 2026 Jonathan
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */


namespace App\Models;

use App\Traits\HasAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Wallet extends Model
{
    use HasUlids, HasAudit;

    protected $fillable = ['name', 'currency', 'created_by', 'updated_by'];

    /**
     * Relación con los usuarios que tienen acceso a esta billetera.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wallet_user')
            ->withPivot('role')
            ->withTimestamps();
    }
}
