<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear permisos básicos
        $permissions = [
            'access-admin-panel',
            'manage-users',
            'manage-products',
            'manage-orders',
            'manage-shipments',
            'verify-payments',
            'manage-settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Crear roles
        $clienteRole = Role::firstOrCreate([
            'name' => UserRole::CLIENTE->value,
            'guard_name' => 'web'
        ]);
        $adminRole = Role::firstOrCreate([
            'name' => UserRole::ADMIN->value,
            'guard_name' => 'web'
        ]);
        $superAdminRole = Role::firstOrCreate([
            'name' => UserRole::SUPER_ADMIN->value,
            'guard_name' => 'web'
        ]);

        // Asignar permisos a roles
        $adminRole->syncPermissions([
            'access-admin-panel',
            'manage-products',
            'manage-orders',
            'manage-shipments',
            'verify-payments',
        ]);

        $superAdminRole->syncPermissions($permissions); // Todos los permisos
    }
}
