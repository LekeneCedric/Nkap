<?php

namespace Code237\Nkap\Account\tests\Unit\CommandBuilder;

use Code237\Nkap\Account\Application\Command\save\SaveAccountCommand;

class SaveAccountCommandBuilder
{
    private string $userId;
    private float $balance;
    private float $totalIncomes;
    private float $totalExpenses;
    private string $lastTransactionDate;
    private string $accountName;
    private string $isIncludeInTotalBalance;
    public static function asBuilder(): SaveAccountCommandBuilder
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

    public function withTotalIncomes(int $value): static
    {
        $this->totalIncomes = $value;
        return $this;
    }

    public function withTotalExpenses(int $value): static
    {
        $this->totalExpenses = $value;
        return $this;
    }

    public function withLastTransactionDate(string $date): static
    {
        $this->lastTransactionDate = $date;
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

    public function build(): SaveAccountCommand
    {
        return new SaveAccountCommand(
            userId: $this->userId,
            balance: $this->balance,
            totalIncomes: $this->totalIncomes,
            totalExpenses: $this->totalExpenses,
            lastTransactionDate: $this->lastTransactionDate,
            accountName: $this->accountName,
            isIncludeInTotalBalance: $this->isIncludeInTotalBalance,
        );
    }
}