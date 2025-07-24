<?php

namespace App\Enums;

enum UserRole: string
{
    case CLIENTE = 'cliente';
    case ADMIN = 'admin';
    case SUPER_ADMIN = 'super_admin';

    /**
     * Obtener el nombre legible del rol
     */
    public function label(): string
    {
        return match ($this) {
            self::CLIENTE => 'Cliente',
            self::ADMIN => 'Administrador',
            self::SUPER_ADMIN => 'Super Administrador',
        };
    }

    /**
     * Obtener la descripción del rol
     */
    public function description(): string
    {
        return match ($this) {
            self::CLIENTE => 'Usuario que puede realizar compras en la tienda',
            self::ADMIN => 'Usuario con acceso al panel administrativo',
            self::SUPER_ADMIN => 'Usuario con acceso completo al sistema',
        };
    }

    /**
     * Obtener el color del badge para mostrar en UI
     */
    public function color(): string
    {
        return match ($this) {
            self::CLIENTE => 'blue',
            self::ADMIN => 'green',
            self::SUPER_ADMIN => 'red',
        };
    }

    /**
     * Obtener el icono del rol
     */
    public function icon(): string
    {
        return match ($this) {
            self::CLIENTE => 'fa-user',
            self::ADMIN => 'fa-user-cog',
            self::SUPER_ADMIN => 'fa-user-crown',
        };
    }

    /**
     * Obtener todos los roles como array para formularios
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
     * Verificar si el rol puede acceder al panel admin
     */
    public function canAccessAdmin(): bool
    {
        return in_array($this, [self::ADMIN, self::SUPER_ADMIN]);
    }

    /**
     * Verificar si el rol puede gestionar usuarios
     */
    public function canManageUsers(): bool
    {
        return in_array($this, [self::SUPER_ADMIN]);
    }
}
