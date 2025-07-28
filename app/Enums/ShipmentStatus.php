<?php

namespace App\Enums;

enum ShipmentStatus: int
{
    case PENDING = 1;
    case DELIVERED = 2;
    case CANCELLED = 3;

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pendiente',
            self::DELIVERED => 'Entregado',
            self::CANCELLED => 'Cancelado',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'yellow',
            self::DELIVERED => 'green',
            self::CANCELLED => 'red',
        };
    }

    public function getValue(): string
    {
        return match ($this) {
            self::PENDING => 'pending',
            self::DELIVERED => 'delivered',
            self::CANCELLED => 'cancelled',
        };
    }

    public static function getAvailableStatuses(): array
    {
        return [
            self::PENDING,
            self::DELIVERED,
            self::CANCELLED,
        ];
    }

    /**
     * Convertir desde string a enum
     */
    public static function fromString(string $value): ?self
    {
        return match ($value) {
            'pending' => self::PENDING,
            'delivered' => self::DELIVERED,
            'cancelled' => self::CANCELLED,
            default => null,
        };
    }

    /**
     * Obtener el valor como string de forma segura
     */
    public static function getValueSafe($status): string
    {
        if (is_object($status) && $status instanceof self) {
            return $status->getValue();
        }

        if (is_string($status)) {
            return $status;
        }

        if (is_int($status)) {
            $enum = self::tryFrom($status);
            return $enum ? $enum->getValue() : 'pending';
        }

        return 'pending';
    }
}
