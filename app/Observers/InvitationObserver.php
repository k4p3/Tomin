<?php

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
