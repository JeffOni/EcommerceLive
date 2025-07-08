<?php

namespace App\Enums;

enum PaymentMethod: int
{
    case TRANSFERENCIA = 0;
    case DEUNA = 1;
    case EFECTIVO = 2;
    // Agrega más métodos según lo requieras

    public function label(): string
    {
        return match ($this) {
            self::TRANSFERENCIA => 'Transferencia',
            self::DEUNA => 'De Una',
            self::EFECTIVO => 'Efectivo',
        // Agrega más métodos según lo requieras
        };
    }
}
