<?php

namespace App\Enums;

enum TypeOfDocuments: int
{
    case CÉDULA = 1;
    case PASSPORT = 2;
    case RUC = 3;
    case DNI = 4;

    public function label(): string
    {
        return match ($this) {
            self::CÉDULA => 'Cédula',
            self::PASSPORT => 'Pasaporte',
            self::RUC => 'RUC',
            self::DNI => 'DNI',
        };
    }
}
