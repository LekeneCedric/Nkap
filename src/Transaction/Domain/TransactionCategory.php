<?php

namespace Code237\Nkap\Transaction\Domain;

use Code237\Nkap\Shared\VO\Datevo;
use Code237\Nkap\Shared\VO\StringVO;

class TransactionCategory
{
    private ?Datevo $createdAt;
    private ?Datevo $updatedAt;
    private ?Datevo $deletedAt;
    public function __construct(
        private Id $id,
        private StringVO $icon,
        private StringVO $label,
    )
    {
        $this->createdAt = new Datevo();
        $this->updatedAt = null;
        $this->deletedAt = null;
    }
}