<?php

namespace Code237\Nkap\Account\tests\Unit\CommandBuilder\Account;

use Code237\Nkap\Account\Application\Command\Account\Create\CreateAccountCommand;

class CreateAccountCommandBuilder
{
    private string $userId;
    private float $balance;
    private string $accountName;
    private string $isIncludeInTotalBalance;
    public static function asBuilder(): CreateAccountCommandBuilder
    {
        return new self();
    }


    public function withUserId(string $value): static
    {
        $this->userId = $value;
        return $this;
    }

    public function withBalance(int $value): static
    {
        $this->balance = $value;
        return $this;
    }

    public function withAccountName(string $value): static
    {
        $this->accountName = $value;
        return $this;
    }

    public function withIsIncludeInTotalBalance(true $isIncludeInTotalBalance): static
    {
        $this->isIncludeInTotalBalance = $isIncludeInTotalBalance;
        return $this;
    }

    public function build(): CreateAccountCommand
    {
        return new CreateAccountCommand(
            userId: $this->userId,
            balance: $this->balance,
            accountName: $this->accountName,
            isIncludeInTotalBalance: $this->isIncludeInTotalBalance,
        );
    }
}