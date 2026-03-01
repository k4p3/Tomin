<?php

/**
 * Copyright (c) 2026 Jonathan
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */


namespace App\Filament\Pages\Tenancy;

use App\Filament\Resources\Wallets\Schemas\WalletForm;
use App\Models\Wallet;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Schemas\Schema;

class RegisterWallet extends RegisterTenant
{
    /**
     * Título de la página de registro.
     */
    public static function getLabel(): string
    {
        return 'Registrar Billetera';
    }

    /**
     * Formulario de registro compatible con Filament v5 (usa Schema).
     */
    public function form(Schema $schema): Schema
    {
        return WalletForm::configure($schema);
    }

    /**
     * Lógica para registrar la nueva billetera y vincularla al usuario actual.
     */
    protected function handleRegistration(array $data): Wallet
    {
        $wallet = Wallet::create($data);

        // Vinculamos al usuario actual como dueño (owner)
        $wallet->users()->attach(auth()->user(), ['role' => 'owner']);

        return $wallet;
    }
}
