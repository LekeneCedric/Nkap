<?php

namespace Code237\Nkap\Account\Application\Command\save;

class SaveAccountCommand
{
    public ?string $iconName;
    public ?string $color;
    public function __construct(
        public string $userId,
        public float $balance,
        public string $accountName,
        public bool $isIncludeInTotalBalance,
    )
    {
        $this->iconName = null;
        $this->color = null;
    }
}