<?php

namespace App\Enums;

enum ExpenseCategory: string
{
    case Alimentaire = 'alimentaire';
    case Boissons = 'boissons';
    case Hygiene = 'hygiène';
    case Entretien = 'entretien';
    case Autre = 'autre';

    public function label(): string
    {
        return match ($this) {
            self::Alimentaire => 'Food',
            self::Boissons => 'Beverages',
            self::Hygiene => 'Hygiene',
            self::Entretien => 'Maintenance',
            self::Autre => 'Other',
        };
    }
}
