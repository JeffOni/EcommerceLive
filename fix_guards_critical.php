<?php

require_once 'vendor/autoload.php';

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Enums\UserRole;

// Inicializar la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CORRECCIÓN CRÍTICA - RECREAR ROLES PARA SANCTUM ===\n\n";

// 1. Eliminar roles existentes que pueden estar en guard incorrecto
echo "1. LIMPIANDO ROLES EXISTENTES:\n";
Role::truncate();
Permission::truncate();
echo "   ✓ Roles y permisos eliminados\n";

// 2. Recrear permisos
echo "\n2. CREANDO PERMISOS:\n";
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
    Permission::create([
        'name' => $permission,
        'guard_name' => 'sanctum'
    ]);
    echo "   ✓ Permiso creado: {$permission} (guard: sanctum)\n";
}

// 3. Recrear roles con guard sanctum
echo "\n3. CREANDO ROLES PARA GUARD SANCTUM:\n";
$roleDefinitions = [
    UserRole::CLIENTE->value => [],
    UserRole::ADMIN->value => [
        'access-admin-panel',
        'manage-products',
        'manage-orders',
        'manage-shipments',
        'verify-payments',
    ],
    UserRole::SUPER_ADMIN->value => $permissions // Todos los permisos
];

foreach ($roleDefinitions as $roleName => $rolePermissions) {
    $role = Role::create([
        'name' => $roleName,
        'guard_name' => 'sanctum'
    ]);

    if (!empty($rolePermissions)) {
        $role->syncPermissions($rolePermissions);
    }

    echo "   ✓ Rol creado: {$roleName} (guard: sanctum)\n";
    echo "     Permisos: " . implode(', ', $rolePermissions) . "\n";
}

// 4. Asignar roles a usuarios admin
echo "\n4. ASIGNANDO ROLES A USUARIOS ADMIN:\n";
$adminEmails = ['Admin@example.com'];

foreach ($adminEmails as $email) {
    $user = \App\Models\User::where('email', $email)->first();
    if ($user) {
        // Limpiar roles existentes
        $user->roles()->detach();

        // Asignar nuevo rol
        $user->assignRole(UserRole::SUPER_ADMIN->value);
        echo "   ✓ Rol 'super_admin' asignado a {$user->email}\n";
    } else {
        echo "   ❌ Usuario {$email} no encontrado\n";
    }
}

// 5. Verificación final
echo "\n5. VERIFICACIÓN FINAL:\n";
$finalRoles = Role::all();
echo "   Total de roles: {$finalRoles->count()}\n";
foreach ($finalRoles as $role) {
    echo "   - {$role->name} (guard: {$role->guard_name})\n";
}

$finalPermissions = Permission::all();
echo "   Total de permisos: {$finalPermissions->count()}\n";

// 6. Probar acceso con nuevo sistema
echo "\n6. PRUEBA DE ACCESO:\n";
$testUser = \App\Models\User::where('email', 'Admin@example.com')->first();
if ($testUser) {
    echo "   Usuario: {$testUser->name}\n";
    echo "   Roles: " . $testUser->roles->pluck('name')->implode(', ') . "\n";
    echo "   Guard de roles: " . $testUser->roles->pluck('guard_name')->implode(', ') . "\n";

    // Probar con guard sanctum
    \Illuminate\Support\Facades\Auth::guard('sanctum')->setUser($testUser);
    $canAccessAdmin = \Illuminate\Support\Facades\Gate::allows('admin-panel');
    echo "   Gate admin-panel (sanctum): " . ($canAccessAdmin ? '✅ SÍ' : '❌ NO') . "\n";
}

echo "\n✅ CORRECCIÓN CRÍTICA COMPLETADA\n";
echo "🔄 Reinicia el servidor y prueba de nuevo el login\n";
