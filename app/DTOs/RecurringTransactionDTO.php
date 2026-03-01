<?php

/**
 * Copyright (c) 2026 Jonathan
 * Licensed under the Polyform Noncommercial License 1.0.0
 * See LICENSE file in the project root for full license information.
 */


namespace App\DTOs;

readonly class RecurringTransactionDTO
{
    public function __construct(
        public string $accountId,
        public ?string $categoryId,
        public ?string $merchantId,
        public float $amount,
        public string $type,
        public string $description,
        public int $dayOfMonth,
        public bool $isShared = true,
        public bool $isActive = true,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            accountId: $data['account_id'],
            categoryId: $data['category_id'] ?? null,
            merchantId: $data['merchant_id'] ?? null,
            amount: (float) $data['amount'],
            type: $data['type'],
            description: $data['description'],
            dayOfMonth: (int) $data['day_of_month'],
            isShared: $data['is_shared'] ?? true,
            isActive: $data['is_active'] ?? true,
        );
    }
}
