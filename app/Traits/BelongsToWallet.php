<?php

/**
 * Copyright (c) 2026 Jonathan
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */


namespace App\Traits;

use App\Models\Scopes\WalletScope;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

trait BelongsToWallet
{
    /**
     * Inicializa el Trait para añadir el Global Scope y asignar el wallet_id.
     */
    protected static function bootBelongsToWallet(): void
    {
        // Aplicar el Scope Global de Billetera
        static::addGlobalScope(new WalletScope());

        // Asignar el wallet_id automáticamente al crear registros
        static::creating(function (Model $model) {
            if ($tenant = Filament::getTenant()) {
                if (empty($model->wallet_id)) {
                    $model->wallet_id = $tenant->id;
                }
            }
        });
    }

    /**
     * Definir la relación con el modelo Wallet.
     */
    public function wallet()
    {
        return $this->belongsTo(\App\Models\Wallet::class);
    }
}
