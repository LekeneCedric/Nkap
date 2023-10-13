<?php

namespace Code237\Nkap\Shared\VO;

use Ramsey\Uuid\Nonstandard\Uuid;

class Id
{
    public function __construct(
        private ?string $value = null
    )
    {
        $this->value = $value ?: Uuid::uuid4();
    }
    public function value(): string
    {
        return $this->value;
    }
}