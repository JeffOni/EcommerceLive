<?php

require_once 'vendor/autoload.php';

use Spatie\Permission\Models\Role;
use App\Enums\UserRole;

// Inicializar la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DIAGNÓSTICO Y CORRECCIÓN DE ROLES ===\n\n";

// 1. Verificar roles existentes
echo "1. ROLES EXISTENTES EN LA BASE DE DATOS:\n";
$existingRoles = Role::all();
if ($existingRoles->count() > 0) {
    foreach ($existingRoles as $role) {
        echo "   - {$role->name} (guard: {$role->guard_name})\n";
    }
} else {
    echo "   ❌ NO HAY ROLES EN LA BASE DE DATOS\n";
}

// 2. Verificar roles definidos en el enum
echo "\n2. ROLES DEFINIDOS EN EL ENUM:\n";
$enumRoles = [
    UserRole::CLIENTE->value,
    UserRole::ADMIN->value,
    UserRole::SUPER_ADMIN->value
];

foreach ($enumRoles as $roleName) {
    echo "   - {$roleName}\n";
}

// 3. Crear roles faltantes
echo "\n3. CREANDO ROLES FALTANTES:\n";
foreach ($enumRoles as $roleName) {
    $role = Role::where('name', $roleName)->where('guard_name', 'web')->first();

    if (!$role) {
        $role = Role::create([
            'name' => $roleName,
            'guard_name' => 'web'
        ]);
        echo "   ✓ Creado rol: {$roleName}\n";
    } else {
        echo "   ✓ Ya existe rol: {$roleName}\n";
    }
}

// 4. Verificar usuarios sin roles
echo "\n4. VERIFICANDO USUARIOS SIN ROLES:\n";
$usersWithoutRoles = \App\Models\User::doesntHave('roles')->get();
if ($usersWithoutRoles->count() > 0) {
    echo "   ⚠️  Usuarios sin roles: {$usersWithoutRoles->count()}\n";
    foreach ($usersWithoutRoles as $user) {
        echo "     - {$user->name} ({$user->email})\n";
    }
} else {
    echo "   ✓ Todos los usuarios tienen roles asignados\n";
}

// 5. Asignar roles a usuarios admin si es necesario
echo "\n5. ASIGNANDO ROLES A USUARIOS ADMIN:\n";
$adminEmails = ['Admin@example.com', 'jeffpbohorquez@outlook.com'];

foreach ($adminEmails as $email) {
    $user = \App\Models\User::where('email', $email)->first();
    if ($user) {
        if (!$user->hasAnyRole(['admin', 'super_admin'])) {
            $user->assignRole('super_admin');
            echo "   ✓ Asignado rol 'super_admin' a {$user->email}\n";
        } else {
            echo "   ✓ {$user->email} ya tiene rol administrativo\n";
        }
    }
}

// 6. Verificación final
echo "\n6. VERIFICACIÓN FINAL:\n";
$finalRoles = Role::all();
echo "   Total de roles creados: {$finalRoles->count()}\n";

try {
    $adminUsers = \App\Models\User::role(['admin', 'super_admin'])->get();
    echo "   Usuarios admin encontrados: {$adminUsers->count()}\n";

    foreach ($adminUsers as $user) {
        echo "     - {$user->name} ({$user->email}) - Roles: " . $user->roles->pluck('name')->implode(', ') . "\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error verificando usuarios admin: " . $e->getMessage() . "\n";
}

echo "\n✅ CORRECCIÓN COMPLETADA\n";
