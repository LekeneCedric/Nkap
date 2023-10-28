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
    private ?AmountVo $totalIncomes;
    private ?AmountVo $totalExpenses;
    private ?Datevo $lastTransactionDate;
    private ?Datevo $updatedAt;

    public function __construct(
        private readonly Id $id,
        private Id          $userId,
        private AmountVo    $balance,
        private StringVO    $accountName,
        private bool        $isIncludeInTotalBalance,
    )
    {
        $this->totalIncomes = new AmountVo(0);
        $this->totalExpenses = new AmountVo(0);
        $this->lastTransactionDate = null;
        $this->iconName = null;
        $this->color = null;
        $this->createdAt = null;
        $this->updatedAt = null;
    }

    public static function create(
        Id        $userId,
        AmountVo  $balance,
        StringVO  $name,
        bool      $isIncludeInTotalBalance,
        ?Id       $id = new Id(),
        ?StringVO $color = null,
        ?StringVO $iconName = null,
    ): Account
    {
        $newAccount = new self(
            id: $id ?: new Id(),
            userId: $userId,
            balance: $balance,
            accountName: $name,
            isIncludeInTotalBalance: $isIncludeInTotalBalance
        );
        $newAccount->iconName = $iconName ?: new StringVO('balance');
        $newAccount->color = $color ?: new StringVO('green');

        return $newAccount;
    }

    public function id(): Id
    {
        return $this->id;
    }
}