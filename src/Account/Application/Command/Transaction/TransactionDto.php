<?php

namespace Code237\Nkap\Account\Application\Command\Transaction;

class TransactionDto
{
    public string $id;
    public string $accountId;
    public string $categoryId;
    public int $type;
    public float $amount;
    public string $description;
    public string $operationDate;
    public string $createdAt;
    public string $updatedAt;
}