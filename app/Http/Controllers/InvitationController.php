<?php

/**
 * Copyright (c) 2026 Jonathan Bailon Segura <jonn59@gmail.com>
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */




namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class InvitationController extends Controller
{
    /**
     * Procesa la aceptación de una invitación a través de un token.
     */
    public function accept(string $token)
    {
        // 1. Buscar la invitación por token
        $invitation = Invitation::where('token', $token)->with('wallet.users')->firstOrFail();

        // 2. Verificar si ha expirado
        if ($invitation->hasExpired()) {
            return redirect('/admin/login')->with('error', 'La invitación ha expirado.');
        }

        // 3. Si el usuario no está logueado, lo mandamos al registro
        if (!Auth::check()) {
            return redirect()->route('filament.admin.auth.register', ['invitation_token' => $token]);
        }

        $user = Auth::user();
        $wallet = $invitation->wallet;
        
        // 4. Unir al usuario si no está ya en la billetera
        if (!$wallet->users->contains($user)) {
            $wallet->users()->attach($user, ['role' => 'contributor']);

            // 5. NOTIFICAR a los dueños de la billetera
            $owners = $wallet->users()->wherePivot('role', 'owner')->get();
            
            Notification::make()
                ->title(__('New Contributor Joined'))
                ->success()
                ->body(__(':name has joined the wallet :wallet.', [
                    'name' => $user->name,
                    'wallet' => $wallet->name,
                ]))
                ->sendToDatabase($owners);
        }

        // 6. Borramos la invitación usada
        $invitation->delete();

        return redirect('/admin')
            ->with('success', '¡Te has unido con éxito a la billetera compartida!');
    }
}
