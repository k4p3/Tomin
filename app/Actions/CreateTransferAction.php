<?php

namespace App\Actions;

use App\DTOs\TransferDTO;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateTransferAction
{
    /**
     * Crea una transferencia entre dos cuentas usando un DTO.
     */
    public function execute(TransferDTO $dto): void
    {
        DB::transaction(function () use ($dto) {
            $transferId = (string) Str::ulid();

            // 1. Movimiento de Salida (Cuenta Origen)
            Transaction::create([
                'account_id' => $dto->fromAccountId,
                'user_id' => auth()->id(),
                'amount' => $dto->amount,
                'type' => 'transfer',
                'description' => __('Transfer to') . ': ' . $dto->toAccountName,
                'transaction_date' => $dto->transactionDate,
                'transfer_id' => $transferId,
                'is_shared' => $dto->isShared,
            ]);

            // 2. Movimiento de Entrada (Cuenta Destino)
            Transaction::create([
                'account_id' => $dto->toAccountId,
                'user_id' => auth()->id(),
                'amount' => $dto->amount,
                'type' => 'income',
                'description' => __('Transfer from') . ': ' . $dto->fromAccountName,
                'transaction_date' => $dto->transactionDate,
                'transfer_id' => $transferId,
                'is_shared' => $dto->isShared,
            ]);
        });
    }
}
