<?php

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
