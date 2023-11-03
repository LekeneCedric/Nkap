<?php

namespace Code237\Nkap\Account\tests\Unit\Account;

use Code237\Nkap\Account\Domain\Account;
use Code237\Nkap\Shared\VO\AmountVO;
use Code237\Nkap\Shared\VO\DateVO;
use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\Shared\VO\StringVO;

class AccountSUT
{
    public Account $account;
    private Id $userId;
    private AmountVO $balance;
    private AmountVO $totalIncomes;
    private AmountVO $totalExpenses;
    private DateVO $lastTransactionDate;
    private StringVO $accountName;
    private StringVO $iconName;
    private StringVO $color;
    private bool $isIncludeInTotalBalance;
    public static function asSUT(): AccountSUT
    {
        $self = new self();
        $self->userId = new Id();
        $self->balance = new AmountVO(2000);
        $self->totalIncomes = new AmountVO(3000);
        $self->totalExpenses = new AmountVO(1000);
        $self->lastTransactionDate = new DateVO('2023-09-30 12:30:00');
        $self->accountName = new StringVO("Mes epargnes");
        $self->iconName = new StringVO('icone');
        $self->color = new StringVO('red');
        $self->isIncludeInTotalBalance = true;

        return $self;
    }

    public function withBalance(float $amountValue): self
    {
        $this->balance = new AmountVO($amountValue);
        return $this;
    }
    public function build(): self
    {
        $this->account = Account::create(
            userId: $this->userId,
            balance: $this->balance,
            name: $this->accountName,
            isIncludeInTotalBalance: $this->isIncludeInTotalBalance,
            totalIncomes: $this->totalIncomes,
            totalExpenses: $this->totalExpenses,
            lastTransactionDate: $this->lastTransactionDate,
            iconName: $this->iconName,
        );

        return $this;
    }

}