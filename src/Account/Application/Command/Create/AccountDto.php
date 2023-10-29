<?php

namespace Code237\Nkap\Account\Application\Command\Create;

class AccountDto
{
    public ?string $id = null;
    public ?string $userId = null;
    public ?string $name = null;
    public ?bool $isIncludeInTotalBalance = null;
    public ?float $balance = null;
    public ?float $totalIncomes = null;
    public ?float $totalExpenses = null;
    public ?string $lastTransactionDate = null;
    public ?string $iconName = null;
    public ?string $color = null;
    public ?string $createdAt = null;
    public ?string $updatedAt = null;
}