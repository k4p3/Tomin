<?php

/**
 * Copyright (c) 2026 Jonathan Bailon Segura <jonn59@gmail.com>
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */




namespace App\Observers;

use App\Mail\WalletInvitationMail;
use App\Models\Invitation;
use Illuminate\Support\Facades\Mail;

class InvitationObserver
{
    /**
     * Se ejecuta después de que se crea una invitación.
     */
    public function created(Invitation $invitation): void
    {
        // Enviamos el correo real usando la configuración SMTP dinámica
        Mail::to($invitation->email)->send(new WalletInvitationMail($invitation));
    }
}
