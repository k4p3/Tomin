<?php

namespace App\Actions;

use App\DTOs\RecurringTransactionDTO;
use App\Models\RecurringTransaction;

class CreateRecurringTransactionAction
{
    public function execute(RecurringTransactionDTO $dto): RecurringTransaction
    {
        return RecurringTransaction::create([
            'account_id' => $dto->accountId,
            'category_id' => $dto->categoryId,
            'merchant_id' => $dto->merchantId,
            'user_id' => auth()->id(),
            'amount' => $dto->amount,
            'type' => $dto->type,
            'description' => $dto->description,
            'day_of_month' => $dto->dayOfMonth,
            'is_shared' => $dto->isShared,
            'is_active' => $dto->isActive,
        ]);
    }
}
