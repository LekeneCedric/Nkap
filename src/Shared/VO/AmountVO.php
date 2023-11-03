<?php

namespace Code237\Nkap\Shared\VO;

use Code237\Nkap\Shared\Enums\DeviceEnum;
use InvalidArgumentException;

class AmountVO
{
    public function __construct(
        private float $amount,
    )
    {
        $this->validate($this->amount);
    }

    /**
     * @return float
     */
    public function value(): float
    {
        return $this->amount;
    }

    public function add(float $amountValue): static
    {
        $this->amount += $amountValue;
        return $this;
    }

    public function remove(float $amountValue): static
    {
        $this->amount -= $amountValue;
        return $this;
    }

    public function isLowerThan(float $amount): bool
    {
        return $this->amount < $amount;
    }

    public function readableValue(): string
    {
        return number_format($this->amount, 2, ',', ' ');
    }

    /**
     * @param int $amount
     * @return void
     */
    private function validate(int $amount): void
    {
        $this->checkIfAmountIsNotNegativeOrThrowException($amount);
    }

    /**
     * @param int $amount
     * @return void
     * @throws InvalidArgumentException
     */
    private function checkIfAmountIsNotNegativeOrThrowException(int $amount): void
    {
        if ($amount < 0) {
            throw new InvalidArgumentException("Le montant ne peux être négatif");
        }
    }
}