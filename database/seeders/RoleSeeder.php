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
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Crear roles
        $clienteRole = Role::firstOrCreate(['name' => UserRole::CLIENTE->value]);
        $adminRole = Role::firstOrCreate(['name' => UserRole::ADMIN->value]);
        $superAdminRole = Role::firstOrCreate(['name' => UserRole::SUPER_ADMIN->value]);

        // Asignar permisos a roles
        $adminRole->syncPermissions([
            'access-admin-panel',
            'manage-products',
            'manage-orders',
            'manage-shipments',
            'verify-payments',
        ]);

        $superAdminRole->syncPermissions($permissions); // Todos los permisos

        // Asignar rol super admin al usuario existente (Admin@example.com)
        $adminUser = \App\Models\User::where('email', 'Admin@example.com')->first();
        if ($adminUser) {
            $adminUser->assignRole(UserRole::SUPER_ADMIN->value);
        }
    }
}
