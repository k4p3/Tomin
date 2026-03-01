<?php

use App\Models\User;
use App\Models\Wallet;
use App\Models\Account;
use Filament\Facades\Filament;

test('a user cannot see accounts from another wallet', function () {
    // 1. Crear dos billeteras y dos usuarios
    $user1 = User::factory()->create();
    $wallet1 = Wallet::create(['name' => 'Wallet 1', 'currency' => 'MXN']);
    $wallet1->users()->attach($user1, ['role' => 'owner']);

    $user2 = User::factory()->create();
    $wallet2 = Wallet::create(['name' => 'Wallet 2', 'currency' => 'MXN']);
    $wallet2->users()->attach($user2, ['role' => 'owner']);

    // 2. Crear una cuenta en la billetera 1
    $account1 = Account::create([
        'wallet_id' => $wallet1->id,
        'name' => 'Secret Account',
        'type' => 'debit',
        'balance' => 1000
    ]);

    // 3. Actuar como usuario 2
    $this->actingAs($user2);

    // 4. Forzar el tenant en Filament para que el Scope lo detecte
    Filament::setTenant($wallet2);

    // 5. Verificar que la cuenta de la billetera 1 NO es visible
    $visibleAccounts = Account::all();
    
    expect($visibleAccounts->count())->toBe(0);
});
