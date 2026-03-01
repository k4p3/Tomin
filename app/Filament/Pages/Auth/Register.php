<?php

/**
 * Copyright (c) 2026 Jonathan
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */


namespace App\Filament\Pages\Auth;

use App\Models\Invitation;
use Filament\Auth\Pages\Register as BaseRegister;
use Illuminate\Database\Eloquent\Model;

class Register extends BaseRegister
{
    /**
     * Sobrescribir el método de creación de usuario para unirlo a la billetera invitada.
     */
    protected function handleRegistration(array $data): Model
    {
        $user = parent::handleRegistration($data);

        // 1. Obtener el token de invitación de la URL si existe
        $token = request()->query('invitation_token');

        if ($token) {
            $invitation = Invitation::where('token', $token)->first();

            // 2. Si la invitación es válida y no ha expirado
            if ($invitation && !$invitation->hasExpired()) {
                // 3. Unir al usuario como colaborador
                $invitation->wallet->users()->attach($user, ['role' => 'contributor']);

                // 4. Borrar la invitación usada
                $invitation->delete();
            }
        }

        return $user;
    }
}
