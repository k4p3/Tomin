<?php

/**
 * Copyright (c) 2026 Jonathan Bailon Segura <jonn59@gmail.com>
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */




namespace App\Models\Scopes;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class WalletScope implements Scope
{
    /**
     * Aplica el filtro de wallet_id a todas las consultas de forma automática.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // 1. Si no hay un tenant, no filtramos.
        if (!Filament::getTenant()) {
            return;
        }

        // 2. Si estamos en consola pero NO estamos en tests, no filtramos (migraciones, seeds).
        if (app()->runningInConsole() && !app()->runningUnitTests()) {
            return;
        }

        // 3. Aplicar filtro
        $builder->where($model->getTable() . '.wallet_id', Filament::getTenant()->id);
    }
}
