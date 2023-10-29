<?php

namespace Code237\Nkap\Account\Domain;

use Code237\Nkap\Shared\VO\AmountVo;
use Code237\Nkap\Shared\VO\Datevo;
use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\Shared\VO\StringVO;

class Account
{
    private ?Datevo $createdAt = null;
    private ?Datevo $updatedAt = null;

    public function __construct(
        private readonly Id $id,
        private Id          $userId,
        private AmountVo    $balance,
        private StringVO    $accountName,
        private bool        $isIncludeInTotalBalance,
        private StringVO $iconName,
        private StringVO $color,
        private AmountVo $totalIncomes,
        private AmountVo $totalExpenses,
        private Datevo $lastTransactionDate,
    )
    {
    }

    public static function create(
        Id        $userId,
        AmountVo  $balance,
        StringVO  $name,
        bool      $isIncludeInTotalBalance,
        ?Id       $id = new Id(),
        ?Datevo $createdAt = new Datevo(),
        ?Datevo $updatedAt = new Datevo(),
        ?AmountVo $totalIncomes = new AmountVo(0),
        ?AmountVo $totalExpenses = new AmountVo(0),
        ?Datevo $lastTransactionDate = new Datevo(),
        ?StringVO $color = new StringVO('green'),
        ?StringVO $iconName = new StringVO('balance'),
    ): Account
    {
        $newAccount = new self(
            id: $id ?: new Id(),
            userId: $userId,
            balance: $balance,
            accountName: $name,
            isIncludeInTotalBalance: $isIncludeInTotalBalance,
            iconName: $iconName,
            color: $color,
            totalIncomes: $totalIncomes,
            totalExpenses: $totalExpenses,
            lastTransactionDate: $lastTransactionDate,
        );
        $newAccount->createdAt = $createdAt;
        $newAccount->updatedAt = $updatedAt;

        return $newAccount;
    }

    public function id(): Id
    {
        return $this->id;
    }

    public function userId(): Id
    {
        return $this->userId;
    }

    public function balance(): AmountVo
    {
        return $this->balance;
    }
    public function name(): StringVO
    {
        return $this->accountName;
    }

    public function isIncludeInTotalBalance(): bool
    {
        return $this->isIncludeInTotalBalance;
    }

    public function totalIncomes(): AmountVo
    {
        return $this->totalIncomes;
    }

    public function totalExpenses(): AmountVo
    {
        return $this->totalExpenses;
    }

    public function lastTransactionDate(): Datevo
    {
        return $this->lastTransactionDate;
    }

    public function iconName(): StringVO
    {
        return $this->iconName;
    }

    public function color(): StringVO
    {
        return $this->color;
    }

    public function createdAt(): Datevo
    {
        return $this->createdAt;
    }

    public function updatedAt(): Datevo
    {
        return $this->updatedAt;
    }

    public function changeName(StringVO $name): void
    {
        $this->accountName = $name;
    }
}