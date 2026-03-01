<?php

/**
 * Copyright (c) 2026 Jonathan
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */


namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WalletInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Invitation $invitation
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('You have been invited to a Shared Wallet'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.wallet-invitation',
            with: [
                'url' => route('invitation.accept', ['token' => $this->invitation->token]),
                'walletName' => $this->invitation->wallet->name,
            ],
        );
    }
}
