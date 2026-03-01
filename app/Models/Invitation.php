<?php

namespace App\Models;

use App\Traits\BelongsToWallet;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invitation extends Model
{
    use HasUlids, BelongsToWallet;

    protected $fillable = ['wallet_id', 'email', 'token', 'expires_at'];

    /**
     * Generar un token único al crear la invitación.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invitation) {
            $invitation->token = Str::random(32);
            $invitation->expires_at = now()->addDays(7); // Expira en 7 días
        });
    }

    /**
     * Determinar si la invitación ha expirado.
     */
    public function hasExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
