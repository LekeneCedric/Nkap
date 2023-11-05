<?php

namespace Code237\Nkap\Account\tests\Unit\CommandBuilder\Transaction;

use Code237\Nkap\Account\Application\Command\Transaction\Create\CreateTransactionCommand;
use Code237\Nkap\Account\Application\Command\Transaction\Update\UpdateTransactionCommand;
use Code237\Nkap\Shared\VO\DateVO;

class UpdateTransactionCommandBuilder
{
    private string $id;
    private string $accountId;
    private string $transactionCategoryId;
    private int $transactionType;
    private float $transactionAmount;
    private string $description;
    private string $operationDate;
    private string $createdAt;
    private string $updatedAt;

    public static function asBuilder(): UpdateTransactionCommandBuilder
    {
        return new self();
    }

    public function withId(string $transactionId): static
    {
        $this->id = $transactionId;
        return $this;
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

    public function withCreatedAt(string $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function withUpdatedAt(string $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
    public function build(): UpdateTransactionCommand
    {
        return new UpdateTransactionCommand(
            id: $this->id,
            accountId: $this->accountId,
            transactionCategoryId: $this->transactionCategoryId,
            transactionType: $this->transactionType,
            transactionAmount: $this->transactionAmount,
            transactionDescription: $this->description,
            transactionOperationDate: $this->operationDate,
            createdAt: $this->createdAt,updatedAt: $this->updatedAt,
        );
    }
}