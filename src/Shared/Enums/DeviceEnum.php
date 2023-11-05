<?php

namespace Code237\Nkap\Shared\Enums;

enum DeviceEnum: int
{
    case XAF = 1;
    case EUR = 2;
    case USD = 3;
    case CNY = 4;

    public function symbol(): string
    {
        return match ($this) {
          DeviceEnum::XAF => '₣',
          DeviceEnum::EUR => '€',
          DeviceEnum::USD => '$',
          DeviceEnum::CNY => '¥',
        };
    }

    public function abbreviation(): string
    {
        return match ($this) {
            DeviceEnum::XAF => 'XAF',
            DeviceEnum::EUR => 'EUR',
            DeviceEnum::USD => 'USD',
            DeviceEnum::CNY => 'CNY'
        };
    }
}
