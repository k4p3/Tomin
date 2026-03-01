<?php

use App\Actions\CreateRecurringTransactionAction;
use App\DTOs\RecurringTransactionDTO;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Account;
use App\Models\RecurringTransaction;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Filament\Facades\Filament;

test('a recurring transaction creates a rule and the command generates transactions', function () {
    // 1. Setup
    $user = User::factory()->create();
    $wallet = Wallet::create(['name' => 'Home', 'currency' => 'MXN']);
    $wallet->users()->attach($user, ['role' => 'owner']);
    $this->actingAs($user);
    Filament::setTenant($wallet);

    $account = Account::create(['name' => 'Debit', 'type' => 'debit', 'balance' => 5000]);

    // 2. Crear la regla de automatización (ej. Renta el día de hoy)
    $today = Carbon::today();
    $dto = new RecurringTransactionDTO(
        accountId: $account->id,
        categoryId: null,
        merchantId: null,
        amount: 1500,
        type: 'expense',
        description: 'Monthly Rent',
        dayOfMonth: $today->day,
        isShared: true,
        isActive: true
    );

    $action = new CreateRecurringTransactionAction();
    $recurringRule = $action->execute($dto);

    expect(RecurringTransaction::count())->toBe(1);
    expect($recurringRule->description)->toBe('Monthly Rent');

    // 3. Ejecutar el comando de procesamiento
    $this->artisan('transactions:process-recurring');

    // 4. Verificaciones
    // Debe existir una nueva transacción real
    expect(Transaction::count())->toBe(1);
    $transaction = Transaction::first();
    expect($transaction->description)->toContain('Monthly Rent');
    expect((float) $transaction->amount)->toBe(1500.00);
    expect($transaction->is_automated)->toBeTrue();

    // La regla debe estar marcada con la última fecha de ejecución
    $recurringRule->refresh();
    expect($recurringRule->last_processed_at->isToday())->toBeTrue();

    // 5. Re-ejecutar el comando (No debe crear duplicados el mismo día)
    $this->artisan('transactions:process-recurring');
    expect(Transaction::count())->toBe(1);
});
