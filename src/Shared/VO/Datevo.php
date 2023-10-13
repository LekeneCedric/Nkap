<?php

namespace Code237\Nkap\Shared\VO;

class Datevo
{
    private string $value;

    public function __construct(
        ?string $value = null
    )
    {
        $this->value = $value ?:date('Y-m-d H:i:s');
        $this->validate();
    }

    private function validate(): void
    {
        $d = \DateTime::createFromFormat('Y-m-d H:i:s', $this->value);

        if (!$d || $d->format('Y-m-d H:i:s') !== $this->value) {
            throw new \InvalidArgumentException("Date entree invalide !");
        }
    }

}