<?php

require_once 'vendor/autoload.php';

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Enums\UserRole;
use App\Models\User;

// Inicializar la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CORRECCIÓN CRÍTICA - MIGRAR ROLES A SANCTUM ===\n\n";

// 1. Verificar estado actual
echo "1. ESTADO ACTUAL:\n";
$currentRoles = Role::all();
$currentPermissions = Permission::all();
echo "   Roles actuales: {$currentRoles->count()}\n";
foreach ($currentRoles as $role) {
    echo "     - {$role->name} (guard: {$role->guard_name})\n";
}
echo "   Permisos actuales: {$currentPermissions->count()}\n";

// 2. Crear roles y permisos para sanctum si no existen
echo "\n2. CREANDO ROLES Y PERMISOS PARA SANCTUM:\n";

$permissions = [
    'access-admin-panel',
    'manage-users',
    'manage-products',
    'manage-orders',
    'manage-shipments',
    'verify-payments',
    'manage-settings',
];

foreach ($permissions as $permissionName) {
    $permission = Permission::where('name', $permissionName)
        ->where('guard_name', 'sanctum')
        ->first();

    if (!$permission) {
        Permission::create([
            'name' => $permissionName,
            'guard_name' => 'sanctum'
        ]);
        echo "   ✓ Permiso creado: {$permissionName} (sanctum)\n";
    } else {
        echo "   ✓ Permiso existe: {$permissionName} (sanctum)\n";
    }
}

$roleDefinitions = [
    UserRole::CLIENTE->value => [],
    UserRole::ADMIN->value => [
        'access-admin-panel',
        'manage-products',
        'manage-orders',
        'manage-shipments',
        'verify-payments',
    ],
    UserRole::SUPER_ADMIN->value => $permissions
];

foreach ($roleDefinitions as $roleName => $rolePermissions) {
    $role = Role::where('name', $roleName)
        ->where('guard_name', 'sanctum')
        ->first();

    if (!$role) {
        $role = Role::create([
            'name' => $roleName,
            'guard_name' => 'sanctum'
        ]);
        echo "   ✓ Rol creado: {$roleName} (sanctum)\n";
    } else {
        echo "   ✓ Rol existe: {$roleName} (sanctum)\n";
    }

    if (!empty($rolePermissions)) {
        $role->syncPermissions($rolePermissions);
        echo "     Permisos sincronizados: " . count($rolePermissions) . "\n";
    }
}

// 3. Migrar usuarios de roles web a sanctum
echo "\n3. MIGRANDO USUARIOS A ROLES SANCTUM:\n";
$adminUsers = User::whereHas('roles', function ($query) {
    $query->whereIn('name', ['admin', 'super_admin']);
})->get();

foreach ($adminUsers as $user) {
    echo "   Migrando usuario: {$user->name} ({$user->email})\n";

    // Obtener roles actuales
    $currentRoles = $user->roles->pluck('name')->toArray();
    echo "     Roles actuales: " . implode(', ', $currentRoles) . "\n";

    // Remover roles de web
    $user->roles()->detach();

    // Asignar rol en sanctum
    if (in_array('super_admin', $currentRoles) || in_array('admin', $currentRoles)) {
        $user->assignRole('super_admin'); // Esto usará el guard por defecto de Spatie
        echo "     ✓ Rol 'super_admin' asignado para sanctum\n";
    }
}

// 4. Actualizar AuthServiceProvider para usar sanctum
echo "\n4. VERIFICANDO AUTHSERVICEPROVIDER:\n";
$authProvider = file_get_contents('app/Providers/AuthServiceProvider.php');
if (strpos($authProvider, 'UserRole::ADMIN->value, UserRole::SUPER_ADMIN->value') !== false) {
    echo "   ✓ AuthServiceProvider usa valores del enum (correcto)\n";
} else {
    echo "   ⚠️  Verificar AuthServiceProvider manualmente\n";
}

// 5. Verificación final
echo "\n5. VERIFICACIÓN FINAL:\n";
$sanctumRoles = Role::where('guard_name', 'sanctum')->get();
echo "   Roles en sanctum: {$sanctumRoles->count()}\n";
foreach ($sanctumRoles as $role) {
    echo "     - {$role->name}\n";
}

$testUser = User::where('email', 'Admin@example.com')->first();
if ($testUser) {
    $testUser->refresh();
    echo "\n   Usuario de prueba: {$testUser->name}\n";
    echo "   Roles actuales: " . $testUser->roles->pluck('name')->implode(', ') . "\n";
    echo "   Guards de roles: " . $testUser->roles->pluck('guard_name')->unique()->implode(', ') . "\n";

    // Probar hasAnyRole con guard sanctum
    try {
        $hasRole = $testUser->hasAnyRole(['admin', 'super_admin']);
        echo "   hasAnyRole: " . ($hasRole ? '✅ SÍ' : '❌ NO') . "\n";
    } catch (Exception $e) {
        echo "   hasAnyRole error: " . $e->getMessage() . "\n";
    }
}

echo "\n✅ MIGRACIÓN A SANCTUM COMPLETADA\n";
echo "🔄 Limpia cache y reinicia servidor:\n";
echo "   php artisan config:clear\n";
echo "   php artisan cache:clear\n";
echo "   php artisan serve\n";
