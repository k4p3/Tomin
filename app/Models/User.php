<?php

/**
 * Copyright (c) 2026 Jonathan
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */


namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laragear\TwoFactor\TwoFactorAuthentication;
use Laragear\TwoFactor\Contracts\TwoFactorAuthenticatable;

class User extends Authenticatable implements FilamentUser, HasTenants, TwoFactorAuthenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthentication;

    /**
     * Determina si el usuario puede acceder al panel de Filament.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return true; // En producción, aquí validarías roles o emails permitidos.
    }

    /**
     * Obtiene las billeteras (tenants) a las que este usuario tiene acceso.
     */
    public function getTenants(Panel $panel): Collection
    {
        return $this->wallets;
    }

    /**
     * Determina si el usuario puede acceder a una billetera (tenant) específica.
     */
    public function canAccessTenant(Model $tenant): bool
    {
        return $this->wallets->contains($tenant);
    }

    /**
     * Relación con las billeteras a las que el usuario tiene acceso.
     */
    public function wallets(): BelongsToMany
    {
        return $this->belongsToMany(Wallet::class, 'wallet_user')
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * El resto de los atributos de User...
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'locale',
        'number_format',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
