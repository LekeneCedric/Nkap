<?php

namespace Code237\Nkap\Account\Application\Command\Account\Update;

class UpdateAccountCommand
{
    public ?string $id = null;
    public ?float $totalIncomes = null;
    public ?float $totalExpenses = null;
    public ?string $lastTransactionDate = null;
    public ?string $createdAt = null;
    public ?string $updatedAt = null;
    public ?string $iconName = null;
    public ?string $color = null;
    public function __construct(
        public string $userId,
        public float $balance,
        public string $accountName,
        public bool $isIncludeInTotalBalance,
    )
    {
    }
}