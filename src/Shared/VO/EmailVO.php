<?php

namespace Code237\Nkap\Shared\VO;

use InvalidArgumentException;

readonly class EmailVO
{
    public function __construct(
        private string $value,
    )
    {
        $this->validate();
    }

    public function value(): string
    {
        return $this->value;
    }
    private function validate(): void
    {
        if (!filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Adresse e-mail invalide !");
        }
    }


}