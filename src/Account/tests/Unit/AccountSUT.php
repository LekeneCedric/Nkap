<?php

namespace Code237\Nkap\Account\tests\Unit;

use Code237\Nkap\Account\Domain\Account;
use Code237\Nkap\Shared\Enums\DeviceEnum;
use Code237\Nkap\Shared\VO\AmountVo;
use Code237\Nkap\Shared\VO\Datevo;
use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\Shared\VO\StringVO;

class AccountSUT
{
    public Account $account;
    private Id $userId;
    private AmountVo $balance;
    private AmountVo $totalIncomes;
    private AmountVo $totalExpenses;
    private Datevo $lastTransactionDate;
    private StringVO $accountName;
    private StringVO $iconName;
    private StringVO $color;
    private bool $isIncludeInTotalBalance;
    public static function asSUT(): AccountSUT
    {
        $self = new self();
        $self->userId = new Id();
        $self->balance = new AmountVo(2000);
        $self->totalIncomes = new AmountVo(3000);
        $self->totalExpenses = new AmountVo(1000);
        $self->lastTransactionDate = new Datevo('2023-09-30 12:30:00');
        $self->accountName = new StringVO("Mes epargnes");
        $self->iconName = new StringVO('icone');
        $self->color = new StringVO('red');
        $self->isIncludeInTotalBalance = true;

        return $self;
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