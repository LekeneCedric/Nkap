<?php

namespace Code237\Nkap\Account\Domain;

use Code237\Nkap\Shared\VO\AmountVo;
use Code237\Nkap\Shared\VO\Datevo;
use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\Shared\VO\StringVO;

class Account
{
    private ?StringVO $iconName;
    private ?StringVO $color;
    private ?Datevo $createdAt;
    private ?Datevo $updatedAt;
    public function __construct(
        private Id $id,
        private Id       $userId,
        private AmountVo $balance,
        private AmountVo $totalIncomes,
        private AmountVo $totalExpenses,
        private Datevo   $lastTransactionDate,
        private StringVO $accountName,
        private bool     $isIncludeInTotalBalance,
    )
    {
        $this->iconName = null;
        $this->color = null;
        $this->createdAt = null;
        $this->updatedAt = null;
    }

    public static function create(
        Id       $userId,
        AmountVo $balance,
        AmountVo $totalIncomes,
        AmountVo $totalExpenses,
        Datevo   $lastTransactionDate,
        StringVO $name,
        bool     $isIncludeInTotalBalance,
        ?Id $id = new Id(),
        ?StringVO $color = null,
        ?StringVO $iconName = null,
    ): Account
    {
        $newAccount = new self(
            id: $id?:new Id(),
            userId: $userId,
            balance: $balance,
            totalIncomes: $totalIncomes,
            totalExpenses: $totalExpenses,
            lastTransactionDate: $lastTransactionDate,
            accountName: $name,
            isIncludeInTotalBalance: $isIncludeInTotalBalance
        );
        $newAccount->iconName = $iconName?: new StringVO('balance');
        $newAccount->color = $color?: new StringVO('green');

        return $newAccount;
    }

    public function id(): Id
    {
        return $this->id;
    }
}