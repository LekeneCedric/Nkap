<?php

namespace Code237\Nkap\Account\Domain;

use Code237\Nkap\Account\Domain\Enums\AccountCategoryEnum;
use Code237\Nkap\Domain\Enums\TransactionTypeEnum;
use Code237\Nkap\Shared\VO\AmountVo;
use Code237\Nkap\Shared\VO\Datevo;
use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\Shared\VO\StringVO;
use Code237\Nkap\Transaction\Domain\Transaction;

class Account
{
    private ?Datevo $createdAt;
    private ?Datevo $updatedAt;
    private ?Datevo $deletedAt;
    /**
     * @var Transaction[]
     */
    private array $transactions = [];

    /**
     * @param Id $id
     * @param Id $userId
     * @param AccountCategoryEnum $accountCategory
     */
    public function __construct(
        private readonly Id $id,
        private readonly Id $userId,
        private AccountCategoryEnum $accountCategory
    )
    {
        $this->createdAt = new Datevo();
        $this->updatedAt = null;
        $this->deletedAt = null;
    }

    public static function create(
        ?Id $id,
        ?Id $userId,
        AccountCategoryEnum $accountCategory,
    ): Account
    {
        return new self(
            id: $id ?? new Id(),
            userId: $userId ?? new Id(),
            accountCategory: $accountCategory,
        );
    }

    /**
     * @param Id $accountId
     * @param TransactionTypeEnum $transactionType
     * @param AmountVo $amount
     * @param Id $transactionCategoryId
     * @param StringVO $description
     * @return void
     */
    public function saveTransaction(
        Id                  $accountId,
        TransactionTypeEnum $transactionType,
        AmountVo            $amount,
        Id                  $transactionCategoryId,
        StringVO            $description,
    ): void
    {
        $newTransaction = Transaction::create(
            accountId: $accountId,
            transactionType: $transactionType,
            amount: $amount,
            transactionCategoryId: $transactionCategoryId,
            description: $description
        );

        $this->transactions[] = $newTransaction;
    }

}