<?php

/**
 * Copyright (c) 2026 Jonathan
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */


namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait HasAudit
{
    /**
     * Inicializa el Trait para registrar quién crea y quién edita.
     */
    protected static function bootHasAudit(): void
    {
        // Al crear un registro
        static::creating(function (Model $model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
                $model->updated_by = Auth::id();
            }
        });

        // Al actualizar un registro
        static::updating(function (Model $model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });
    }

    /**
     * Relación con el usuario que creó el registro.
     */
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Relación con el usuario que editó el registro por última vez.
     */
    public function editor()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }
}
