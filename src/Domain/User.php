<?php

namespace Code237\Nkap\Domain;

use Code237\Nkap\Shared\VO\Datevo;
use Code237\Nkap\Shared\VO\EmailVo;
use Code237\Nkap\Shared\VO\Id;
use Code237\Nkap\Shared\VO\StringVO;

class User
{

    private ?Datevo $createdAt;
    private ?Datevo $updatedAt;
    private ?Datevo $deletedAt;
    public function __construct(
        private readonly Id $id,
        private StringVO $name,
        private StringVO $surName,
        private EmailVo $email,
        private array $accounts,
    )
    {
        $this->createdAt = new Datevo();
        $this->updatedAt = null;
        $this->deletedAt = null;
    }

    public static function create(
        ?Id $id,
        StringVO $name,
        StringVO $surname,
        EmailVo $email,
    ): self
    {
        return new self(
            id: $id ?? new Id(),
            name: $name,
            surName: $surname,
            email: $email,
            accounts: []
        );
    }
}