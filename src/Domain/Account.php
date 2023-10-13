<?php

namespace Code237\Nkap\Domain;

use Code237\Nkap\Domain\Enums\AccountCategoryEnum;
use Code237\Nkap\Shared\VO\Datevo;
use Code237\Nkap\Shared\VO\Id;

class Account
{
    private ?Datevo $createdAt;
    private ?Datevo $updatedAt;
    private ?Datevo $deletedAt;

    public function __construct(
        private readonly Id $id,
        private AccountCategoryEnum $accountCategory,
        private array $transactions
    )
    {
        $this->createdAt = new Datevo();
        $this->updatedAt = null;
        $this->deletedAt = null;
    }

}