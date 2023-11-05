<?php

namespace Code237\Nkap\Account\Domain;

use Code237\Nkap\Shared\VO\DateVO;
use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\Shared\VO\StringVO;

class TransactionCategory
{
    private ?DateVO $createdAt = null;
    private ?DateVO $updatedAt = null;
    public function __construct(
        private Id $id,
        private StringVO $name,
        private StringVO $icon,
        private StringVO $color,
        private StringVO $description,
    )
    {
        $this->createdAt = new DateVO();
        $this->updatedAt = new DateVO();
    }

    public static function create(
        StringVO $name,
        StringVO $icon,
        StringVO $color,
        StringVO $description,
        ?Id $id = new Id(),
    ): TransactionCategory
    {
        return new self(
            id: $id,
            name: $name,
            icon: $icon,
            color: $color,
            description: $description,
        );
    }

    public function id(): Id
    {
        return $this->id;
    }
}