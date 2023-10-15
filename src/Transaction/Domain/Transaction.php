<?php

namespace Code237\Nkap\Transaction\Domain;

use Code237\Nkap\Domain\Enums\TransactionTypeEnum;
use Code237\Nkap\Shared\VO\AmountVo;
use Code237\Nkap\Shared\VO\Datevo;
use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\Shared\VO\StringVO;

class Transaction
{
    private Datevo $createdAt;
    private Datevo $updatedAt;
    private Datevo $deletedAt;

    /**
     * @param Id $id
     * @param Id $accountId
     * @param TransactionTypeEnum $transactionType
     * @param AmountVo $amount
     * @param Id $transactionCategoryId
     * @param StringVO $description
     */
    public function __construct(
        private readonly Id $id,
        private readonly Id $accountId,
        private TransactionTypeEnum $transactionType,
        private AmountVo $amount,
        private Id $transactionCategoryId,
        private StringVO $description,
    )
    {
        $this->createdAt = new Datevo();
        $this->updatedAt = null;
        $this->deletedAt = null;
    }

    /**
     * @param Id|null $accountId
     * @param TransactionTypeEnum $transactionType
     * @param AmountVo $amount
     * @param Id $transactionCategoryId
     * @param StringVO $description
     * @param Id|null $id
     * @return self
     */
    public static function create(
        ?Id                 $accountId,
        TransactionTypeEnum $transactionType,
        AmountVo            $amount,
        Id                  $transactionCategoryId,
        StringVO            $description,
        ?Id                 $id = null,
    ): self
    {
        return new self(
            id: $id ?? new Id(),
            accountId: $accountId ?? new Id(),
            transactionType: $transactionType,
            amount: $amount,
            transactionCategoryId: $transactionCategoryId,
            description: $description
        );
    }
}