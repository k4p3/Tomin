<?php

namespace App\DTOs;

use Illuminate\Support\Carbon;

readonly class TransferDTO
{
    public function __construct(
        public string $fromAccountId,
        public string $toAccountId,
        public string $fromAccountName,
        public string $toAccountName,
        public float $amount,
        public Carbon $transactionDate,
        public bool $isShared = true,
    ) {}

    /**
     * Crea un DTO desde un arreglo de datos (típicamente de un formulario de Filament).
     */
    public static function fromArray(array $data): self
    {
        return new self(
            fromAccountId: $data['from_account_id'],
            toAccountId: $data['to_account_id'],
            fromAccountName: $data['from_account_name'],
            toAccountName: $data['to_account_name'],
            amount: (float) $data['amount'],
            transactionDate: Carbon::parse($data['transaction_date']),
            isShared: $data['is_shared'] ?? true,
        );
    }
}
