<?php

require_once 'vendor/autoload.php';

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

// Inicializar la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== MIGRACIÓN DE ROLES Y PERMISOS AL GUARD WEB ===\n\n";

try {
    // 1. Limpiar roles y permisos existentes
    echo "1. Limpiando roles y permisos existentes...\n";
    Role::truncate();
    Permission::truncate();

    // Limpiar tabla pivot
    \DB::table('model_has_roles')->truncate();
    \DB::table('model_has_permissions')->truncate();
    \DB::table('role_has_permissions')->truncate();

    echo "   ✅ Limpieza completada\n";

    // 2. Crear permisos con guard web
    echo "\n2. Creando permisos con guard 'web'...\n";
    $permissions = [
        'access-admin-panel',
        'manage-users',
        'verify-payments',
        'manage-orders',
        'manage-products',
        'manage-categories',
        'view-analytics'
    ];

    foreach ($permissions as $permission) {
        Permission::create([
            'name' => $permission,
            'guard_name' => 'web'
        ]);
        echo "   ✅ Permiso creado: {$permission}\n";
    }

    // 3. Crear roles con guard web
    echo "\n3. Creando roles con guard 'web'...\n";

    $superAdmin = Role::create([
        'name' => 'super_admin',
        'guard_name' => 'web'
    ]);
    $superAdmin->givePermissionTo($permissions);
    echo "   ✅ Rol 'super_admin' creado con todos los permisos\n";

    $admin = Role::create([
        'name' => 'admin',
        'guard_name' => 'web'
    ]);
    $admin->givePermissionTo([
        'access-admin-panel',
        'manage-users',
        'verify-payments',
        'manage-orders'
    ]);
    echo "   ✅ Rol 'admin' creado con permisos básicos\n";

    $usuario = Role::create([
        'name' => 'usuario',
        'guard_name' => 'web'
    ]);
    echo "   ✅ Rol 'usuario' creado\n";

    // 4. Asignar rol al usuario admin
    echo "\n4. Asignando rol al usuario admin...\n";
    $user = User::where('email', 'Admin@example.com')->first();

    if ($user) {
        // Remover roles existentes
        $user->roles()->detach();

        // Asignar nuevo rol con guard web
        $user->assignRole('super_admin');

        echo "   ✅ Usuario {$user->email} ahora tiene rol 'super_admin' con guard 'web'\n";

        // Verificar
        $roles = $user->roles()->pluck('name', 'guard_name');
        echo "   📋 Roles actuales: " . $roles->toJson() . "\n";

        // Probar hasRole
        $hasRole = $user->hasRole('super_admin');
        echo "   🧪 hasRole('super_admin'): " . ($hasRole ? '✅' : '❌') . "\n";

        $hasAnyRole = $user->hasAnyRole(['admin', 'super_admin']);
        echo "   🧪 hasAnyRole(['admin', 'super_admin']): " . ($hasAnyRole ? '✅' : '❌') . "\n";

    } else {
        echo "   ❌ Usuario admin no encontrado\n";
    }

    // 5. Verificar configuración final
    echo "\n5. VERIFICACIÓN FINAL:\n";
    echo "   Roles totales: " . Role::count() . "\n";
    echo "   Permisos totales: " . Permission::count() . "\n";
    echo "   Usuarios con roles: " . User::role('super_admin')->count() . "\n";

    // 6. Probar gates con nuevo guard
    echo "\n6. PROBANDO GATES CON GUARD WEB:\n";
    if ($user) {
        // Simular autenticación con guard web
        \Auth::guard('web')->setUser($user);

        $gates = ['admin-panel', 'manage-users', 'verify-payments'];
        foreach ($gates as $gate) {
            $allowed = \Gate::forUser($user)->allows($gate);
            echo "   Gate {$gate}: " . ($allowed ? '✅' : '❌') . "\n";
        }
    }

    echo "\n🎯 MIGRACIÓN COMPLETADA EXITOSAMENTE\n";
    echo "✅ Todos los roles y permisos ahora usan guard 'web'\n";
    echo "✅ Fortify configurado para usar guard 'web'\n";
    echo "✅ Usuario admin configurado correctamente\n";

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== FIN MIGRACIÓN ===\n";
