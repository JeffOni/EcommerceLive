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

    /**
     * Obtener todos los tipos de documento como array para formularios
     */
    public static function toArray(): array
    {
        $cases = [];
        foreach (self::cases() as $case) {
            $cases[$case->value] = $case->label();
        }
        return $cases;
    }
}
