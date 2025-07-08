<?php

namespace App\Enums;

enum OrderStatus: int
{
    case PENDIENTE = 1;
    case PAGADO = 2;
    case PREPARANDO = 3;
    case ENVIADO = 4;
    case ENTREGADO = 5;
    case CANCELADO = 6;
    case DEVUELTO = 7;

    /**
     * Obtener el nombre legible del estado
     */
    public function label(): string
    {
        return match ($this) {
            self::PENDIENTE => 'Pendiente de Pago',
            self::PAGADO => 'Pago Confirmado',
            self::PREPARANDO => 'Preparando Pedido',
            self::ENVIADO => 'En Camino',
            self::ENTREGADO => 'Entregado',
            self::CANCELADO => 'Cancelado',
            self::DEVUELTO => 'Devuelto',
        };
    }

    /**
     * Obtener el color del badge para mostrar en UI
     */
    public function color(): string
    {
        return match ($this) {
            self::PENDIENTE => 'yellow',
            self::PAGADO => 'blue',
            self::PREPARANDO => 'indigo',
            self::ENVIADO => 'purple',
            self::ENTREGADO => 'green',
            self::CANCELADO => 'red',
            self::DEVUELTO => 'orange',
        };
    }

    /**
     * Obtener el icono del estado
     */
    public function icon(): string
    {
        return match ($this) {
            self::PENDIENTE => 'fa-clock',
            self::PAGADO => 'fa-credit-card',
            self::PREPARANDO => 'fa-box',
            self::ENVIADO => 'fa-truck',
            self::ENTREGADO => 'fa-check-circle',
            self::CANCELADO => 'fa-times-circle',
            self::DEVUELTO => 'fa-undo',
        };
    }

    /**
     * Obtener la descripción del estado
     */
    public function description(): string
    {
        return match ($this) {
            self::PENDIENTE => 'El pedido está pendiente de confirmación de pago',
            self::PAGADO => 'El pago ha sido confirmado y verificado',
            self::PREPARANDO => 'Estamos preparando tu pedido para el envío',
            self::ENVIADO => 'Tu pedido está en camino hacia la dirección de entrega',
            self::ENTREGADO => 'El pedido ha sido entregado exitosamente',
            self::CANCELADO => 'El pedido ha sido cancelado',
            self::DEVUELTO => 'El pedido ha sido devuelto',
        };
    }

    /**
     * Verificar si el estado permite cancelación
     */
    public function canBeCancelled(): bool
    {
        return in_array($this, [
            self::PENDIENTE,
            self::PAGADO,
            self::PREPARANDO,
        ]);
    }

    /**
     * Verificar si el estado permite devolución
     */
    public function canBeReturned(): bool
    {
        return $this === self::ENTREGADO;
    }

    /**
     * Obtener los siguientes estados posibles
     */
    public function nextStates(): array
    {
        return match ($this) {
            self::PENDIENTE => [self::PAGADO, self::CANCELADO],
            self::PAGADO => [self::PREPARANDO, self::CANCELADO],
            self::PREPARANDO => [self::ENVIADO, self::CANCELADO],
            self::ENVIADO => [self::ENTREGADO],
            self::ENTREGADO => [self::DEVUELTO],
            self::CANCELADO => [],
            self::DEVUELTO => [],
        };
    }

    /**
     * Obtener todos los estados como array para formularios
     */
    public static function toArray(): array
    {
        $cases = [];
        foreach (self::cases() as $case) {
            $cases[$case->value] = $case->label();
        }
        return $cases;
    }

    /**
     * Obtener estados que requieren atención del admin
     */
    public static function needsAttention(): array
    {
        return [
            self::PENDIENTE,
            self::DEVUELTO,
        ];
    }

    /**
     * Obtener estados activos (que no están finalizados)
     */
    public static function activeStates(): array
    {
        return [
            self::PENDIENTE,
            self::PAGADO,
            self::PREPARANDO,
            self::ENVIADO,
        ];
    }

    /**
     * Obtener estados finales (que ya no cambian)
     */
    public static function finalStates(): array
    {
        return [
            self::ENTREGADO,
            self::CANCELADO,
            self::DEVUELTO,
        ];
    }
}
