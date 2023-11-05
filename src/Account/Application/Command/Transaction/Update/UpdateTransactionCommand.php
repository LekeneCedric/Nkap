<?php

namespace Code237\Nkap\Account\Application\Command\Transaction\Update;

class UpdateTransactionCommand
{
    public function __construct(
        public string $id,
        public string $accountId,
        public string $transactionCategoryId,
        public int    $transactionType,
        public float  $transactionAmount,
        public string $transactionDescription,
        public string $transactionOperationDate,
        public string $createdAt,
        public string $updatedAt,
    )
    {
    }
}