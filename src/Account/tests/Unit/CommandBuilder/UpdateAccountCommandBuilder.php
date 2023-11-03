<?php

namespace Code237\Nkap\Account\tests\Unit\CommandBuilder;


use Code237\Nkap\Account\Application\Command\Account\Update\UpdateAccountCommand;

class UpdateAccountCommandBuilder
{
    private ?string $id;
    private ?float $totalIncomes;
    private ?float $totalExpenses;
    private ?string $lastTransactionDate;
    private ?string $iconName;
    private ?string $color;
    private ?string $createdAt;
    private ?string $updatedAt;
    private string $userId;
    private float $balance;
    private string $accountName;
    private string $isIncludeInTotalBalance;
    public static function asBuilder(): UpdateAccountCommandBuilder
    {
        return new self();
    }

    public function withId(string $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function withTotalIncomes(float $value): static
    {
        $this->totalIncomes = $value;
        return $this;
    }

    public function withTotalExpenses(float $value): static
    {
        $this->totalExpenses = $value;
        return $this;
    }

    public function withLastTransactionDate(string $value): static
    {
        $this->lastTransactionDate = $value;
        return $this;
    }

    public function withIconName(string $value): static
    {
        $this->iconName = $value;
        return $this;
    }

    public function withColor(string $value): static
    {
        $this->color = $value;
        return $this;
    }

    public function withCreatedAt(string $value): static
    {
        $this->createdAt = $value;
        return $this;
    }

    public function withUpdatedAt(string $value): static
    {
        $this->updatedAt = $value;
        return $this;
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

    public function build(): UpdateAccountCommand
    {
        $command = new UpdateAccountCommand(
            userId: $this->userId,
            balance: $this->balance,
            accountName: $this->accountName,
            isIncludeInTotalBalance: $this->isIncludeInTotalBalance,
        );
        $command->id = $this->id;
        $command->totalIncomes = $this->totalIncomes;
        $command->totalExpenses = $this->totalExpenses;
        $command->lastTransactionDate = $this->lastTransactionDate;
        $command->iconName = $this->iconName;
        $command->color = $this->color;
        $command->createdAt = $this->createdAt;
        $command->updatedAt = $this->updatedAt;

        return $command;
    }
}