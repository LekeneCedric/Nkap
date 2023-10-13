<?php

namespace Code237\Nkap\Shared\VO;

readonly class StringVO
{
    public function __construct(
        private string $value
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
        if (empty(trim($this->value))) {
            throw new \InvalidArgumentException("Chaine de caractere invalide !");
        }
    }
}