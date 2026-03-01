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
        // Si estamos en la terminal (ej. migrations, seeds) o no hay un tenant, no filtramos.
        if (app()->runningInConsole() || !Filament::getTenant()) {
            return;
        }

        // Especificamos la tabla del modelo para evitar ambigüedades en JOINS
        $builder->where($model->getTable() . '.wallet_id', Filament::getTenant()->id);
    }
}
