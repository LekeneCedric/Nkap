<?php

namespace Code237\Nkap\Domain;

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
    public function __construct(
        private readonly Id $id,
        private TransactionTypeEnum $transactionType,
        private AmountVo $amount,
        private TransactionCategory $category,
        private StringVO $description,
    )
    {
        $this->createdAt = new Datevo();
        $this->updatedAt = null;
        $this->deletedAt = null;
    }

    public static function create(
        ?Id $id,
        TransactionTypeEnum $transactionType,
        AmountVo $amount,
        TransactionCategory $category,
        StringVO $description
    ): self
    {
        return new self(
            id: $id ?? new Id(),
            transactionType: $transactionType,
            amount: $amount,
            category: $category,
            description: $description
        );
    }
}