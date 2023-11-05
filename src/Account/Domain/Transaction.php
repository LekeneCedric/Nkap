<?php

namespace Code237\Nkap\Account\Domain;

use Code237\Nkap\Shared\Enums\TransactionTypeEnum;
use Code237\Nkap\Shared\VO\AmountVO;
use Code237\Nkap\Shared\VO\DateVO;
use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\Shared\VO\StringVO;

class Transaction
{

    public function __construct(
        private Id                  $id,
        private Id                  $accountId,
        private Id                  $transactionCategoryId,
        private TransactionTypeEnum $transactionType,
        private AmountVO            $transactionAmount,
        private StringVO            $description,
        private DateVO              $operationDate,
        private DateVO              $createdAt,
        private DateVO              $updatedAt,
    )
    {
    }

    public static function create(
        Id                  $accountId,
        Id                  $transactionCategoryId,
        TransactionTypeEnum $type,
        AmountVO            $amount,
        StringVO            $description,
        DateVO              $operationDate,
        ?Id                 $id = new Id(),
        ?DateVO $createdAt = new DateVO(),
        ?DateVO $updatedAt = new DateVO(),
    ): Transaction
    {
        return new self(
            id: $id,
            accountId: $accountId,
            transactionCategoryId: $transactionCategoryId,
            transactionType: $type,
            transactionAmount: $amount,
            description: $description,
            operationDate: $operationDate,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );
    }

    public function id(): Id
    {
        return $this->id;
    }
    public function accountId(): Id
    {
        return $this->accountId;
    }
    public function amount(): AmountVO
    {
        return $this->transactionAmount;
    }

    public function transactionType(): TransactionTypeEnum
    {
        return $this->transactionType;
    }

    public function transactionCategory(): Id
    {
        return $this->transactionCategoryId;
    }

    public function description(): StringVO
    {
        return $this->description;
    }

    public function operationDate(): DateVO
    {
        return $this->operationDate;
    }

    public function type(): TransactionTypeEnum
    {
        return $this->transactionType;
    }

    public function createdAt(): DateVO
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateVO
    {
        return $this->updatedAt;
    }

    public function changeAmount(float $value): void
    {
        $this->transactionAmount = new AmountVO($value);
    }


}