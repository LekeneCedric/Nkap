<?php

namespace Code237\Nkap\Account\tests\Unit\CommandBuilder;

use Code237\Nkap\Account\Application\Command\Transaction\Create\CreateTransactionCommand;

class CreateTransactionCommandBuilder
{
    private string $accountId;
    private string $transactionCategoryId;
    private int $transactionType;
    private float $transactionAmount;
    private string $description;
    private string $operationDate;

    public static function asBuilder(): CreateTransactionCommandBuilder
    {
        return new self();
    }

    public function withAccountId(string $accountId): static
    {
        $this->accountId = $accountId;
        return $this;
    }

    public function withTransactionCategoryId(string $transactionCategoryId): static
    {
        $this->transactionCategoryId = $transactionCategoryId;
        return $this;
    }

    public function withTransactionType(int $transactionType): static
    {
        $this->transactionType = $transactionType;
        return $this;
    }

    public function withTransactionAmount(float $transactionAmount): static
    {
        $this->transactionAmount = $transactionAmount;
        return $this;
    }

    public function withDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function withOperationDate(string $operationDate): static
    {
        $this->operationDate = $operationDate;
        return $this;
    }

    public function build(): CreateTransactionCommand
    {
        return new CreateTransactionCommand(
            accountId: $this->accountId,
            transactionCategoryId: $this->transactionCategoryId,
            transactionType: $this->transactionType,
            transactionAmount: $this->transactionAmount,
            transactionDescription: $this->description,
            transactionOperationDate: $this->operationDate,
        );
    }

}