<?php

namespace Code237\Nkap\Shared\Enums;

enum TransactionTypeEnum: int
{
    case INCOME = 1;
    case EXPENSE = 2;

    public function value(): string
    {
        return match ($this) {
           TransactionTypeEnum::INCOME => "Revenus",
           TransactionTypeEnum::EXPENSE => "Dépense",
        };
    }

    public static function in(int $value): TransactionTypeEnum
    {
        return self::isValid($value);
    }

    private static function isValid(int $value): TransactionTypeEnum
    {
        $self = self::tryFrom($value);
        if (!$self) {
            throw new \InvalidArgumentException("le type de transaction entrée n'est pas prise en compte !");
        }
        return $self;
    }
}
