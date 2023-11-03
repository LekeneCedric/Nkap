<?php

namespace Code237\Nkap\Account\Application\Command\Transaction\Create;

class CreateTransactionCommand
{
    public function __construct(
        public string $accountId,
        public string $transactionCategoryId,
        public int    $transactionType,
        public float  $transactionAmount,
        public string $transactionDescription,
        public string $transactionOperationDate
    )
    {
    }
}