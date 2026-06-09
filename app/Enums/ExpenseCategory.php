<?php

namespace App\Enums;

enum ExpenseCategory: string
{
    case Food = 'food';
    case Drinks = 'drinks';
    case Hygiene = 'hygiene';
    case Cleaning = 'cleaning';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Food => 'Food',
            self::Drinks => 'Beverages',
            self::Hygiene => 'Hygiene',
            self::Cleaning => 'Cleaning',
            self::Other => 'Other',
        };
    }
}
