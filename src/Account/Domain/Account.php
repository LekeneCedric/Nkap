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
        ?Datevo $createdAt = null,
        ?Datevo $updatedAt = null,
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
        $newAccount->createdAt = $createdAt?: new Datevo();
        $newAccount->updatedAt = $updatedAt?: new Datevo();
        $newAccount->lastTransactionDate = new Datevo();
        $newAccount->iconName = $iconName ?: new StringVO('balance');
        $newAccount->color = $color ?: new StringVO('green');

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
}