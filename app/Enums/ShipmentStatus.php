<?php

namespace App\Enums;

enum ShipmentStatus: int
{
    case PENDING = 1;
    case ASSIGNED = 2;
    case PICKED_UP = 3;
    case IN_TRANSIT = 4;
    case DELIVERED = 5;
    case FAILED = 6;

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pendiente de asignación',
            self::ASSIGNED => 'Asignado a repartidor',
            self::PICKED_UP => 'Recogido',
            self::IN_TRANSIT => 'En camino',
            self::DELIVERED => 'Entregado',
            self::FAILED => 'Falló la entrega',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'yellow',
            self::ASSIGNED => 'blue',
            self::PICKED_UP => 'purple',
            self::IN_TRANSIT => 'orange',
            self::DELIVERED => 'green',
            self::FAILED => 'red',
        };
    }

    public static function getAvailableStatuses(): array
    {
        return [
            self::PENDING,
            self::ASSIGNED,
            self::PICKED_UP,
            self::IN_TRANSIT,
            self::DELIVERED,
            self::FAILED,
        ];
    }
}
