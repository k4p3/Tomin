<?php

use App\Models\User;
use App\Models\Wallet;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Filament\Facades\Filament;

test('system records who created and updated a transaction', function () {
    // 1. Setup - Usuario Creador
    $creator = User::factory()->create(['name' => 'Creator User']);
    $wallet = Wallet::create(['name' => 'Shared Wallet', 'currency' => 'MXN']);
    $wallet->users()->attach($creator, ['role' => 'owner']);
    
    $this->actingAs($creator);
    Filament::setTenant($wallet);

    $account = Account::create(['name' => 'Cash', 'type' => 'cash', 'balance' => 1000]);

    // 2. Crear Transacción
    $transaction = Transaction::create([
        'account_id' => $account->id,
        'user_id' => $creator->id,
        'amount' => 100,
        'type' => 'expense',
        'description' => 'Coffee',
        'transaction_date' => Carbon::today(),
    ]);

    // Verificar Creador
    expect($transaction->created_by)->toBe($creator->id);
    expect($transaction->creator->name)->toBe('Creator User');

    // 3. Setup - Usuario Editor (Colaborador)
    $editor = User::factory()->create(['name' => 'Editor User']);
    $wallet->users()->attach($editor, ['role' => 'contributor']);
    
    $this->actingAs($editor);
    // Refresh tenant context for the new user
    Filament::setTenant($wallet);

    // 4. Actualizar Transacción
    $transaction->update(['description' => 'Large Coffee']);

    // 5. Verificaciones Finales
    $transaction->refresh();
    expect($transaction->created_by)->toBe($creator->id); // El creador NO cambia
    expect($transaction->updated_by)->toBe($editor->id);  // El editor SÍ cambia
    expect($transaction->editor->name)->toBe('Editor User');
});
