<?php

/**
 * Copyright (c) 2026 Jonathan Bailon Segura <jonn59@gmail.com>
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */




namespace App\DTOs;

use Illuminate\Support\Carbon;

readonly class InstallmentPurchaseDTO
{
    public function __construct(
        public string $accountId,
        public string $merchantId,
        public ?string $categoryId,
        public float $totalAmount,
        public int $totalInstallments,
        public float $installmentAmount,
        public Carbon $transactionDate,
        public string $description,
        public bool $isShared = true,
    ) {}

    /**
     * Crea un DTO desde un arreglo de datos del formulario.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            accountId: $data['account_id'],
            merchantId: $data['merchant_id'],
            categoryId: $data['category_id'] ?? null,
            totalAmount: (float) $data['total_amount'],
            totalInstallments: (int) $data['total_installments'],
            installmentAmount: (float) $data['installment_amount'],
            transactionDate: Carbon::parse($data['transaction_date']),
            description: $data['description'],
            isShared: $data['is_shared'] ?? true,
        );
    }
}
