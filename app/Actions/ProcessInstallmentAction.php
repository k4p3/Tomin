<?php

namespace App\Actions;

use App\DTOs\InstallmentPurchaseDTO;
use App\Models\InstallmentPurchase;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class ProcessInstallmentAction
{
    /**
     * Procesa una nueva compra a Meses Sin Intereses usando un DTO.
     */
    public function execute(InstallmentPurchaseDTO $dto): Transaction
    {
        return DB::transaction(function () use ($dto) {
            // 1. Crear la transacción base (primer pago)
            $transaction = Transaction::create([
                'account_id' => $dto->accountId,
                'category_id' => $dto->categoryId,
                'merchant_id' => $dto->merchantId,
                'user_id' => auth()->id(),
                'amount' => $dto->installmentAmount,
                'type' => 'expense',
                'description' => $dto->description . " (Mensualidad 1/" . $dto->totalInstallments . ")",
                'transaction_date' => $dto->transactionDate,
                'is_shared' => $dto->isShared,
            ]);

            // 2. Crear el registro del plan de MSI
            InstallmentPurchase::create([
                'wallet_id' => $transaction->wallet_id, // Heredado vía Trait/Scope
                'transaction_id' => $transaction->id,
                'total_installments' => $dto->totalInstallments,
                'remaining_installments' => $dto->totalInstallments - 1,
                'day_of_month' => $dto->transactionDate->day,
            ]);

            return $transaction;
        });
    }
}
