<?php

use App\Actions\ProcessInstallmentAction;
use App\DTOs\InstallmentPurchaseDTO;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Account;
use App\Models\Merchant;
use App\Models\Transaction;
use App\Models\InstallmentPurchase;
use Illuminate\Support\Carbon;
use Filament\Facades\Filament;

test('a high-value purchase can be split into installments', function () {
    // 1. Setup
    $user = User::factory()->create();
    $wallet = Wallet::create(['name' => 'Home', 'currency' => 'MXN']);
    $wallet->users()->attach($user, ['role' => 'owner']);
    $this->actingAs($user);
    Filament::setTenant($wallet);

    $account = Account::create(['name' => 'Visa', 'type' => 'credit', 'balance' => 0]);
    $merchant = Merchant::create(['name' => 'Apple Store']);

    // 2. Ejecutar el Action con DTO
    $dto = new InstallmentPurchaseDTO(
        accountId: $account->id,
        merchantId: $merchant->id,
        categoryId: null,
        totalAmount: 12000,
        totalInstallments: 12,
        installmentAmount: 1000,
        transactionDate: Carbon::today(),
        description: 'iPhone 15',
        isShared: true
    );

    $action = new ProcessInstallmentAction();
    $transaction = $action->execute($dto);

    // 3. Verificaciones
    expect(Transaction::count())->toBe(1);
    expect((float) $transaction->amount)->toBe(1000.00);
    
    $plan = InstallmentPurchase::where('transaction_id', $transaction->id)->first();
    expect($plan)->not->toBeNull();
    expect($plan->remaining_installments)->toBe(11);
});
