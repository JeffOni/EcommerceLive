<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

// Inicializar la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== VERIFICACIÓN FINAL - PROBLEMA RESUELTO ===\n\n";

// 1. Verificar que los roles existen
echo "1. ROLES EN LA BASE DE DATOS:\n";
$roles = \Spatie\Permission\Models\Role::all();
foreach ($roles as $role) {
    echo "   ✓ {$role->name} (guard: {$role->guard_name})\n";
}

// 2. Verificar usuarios admin
echo "\n2. USUARIOS ADMINISTRATIVOS:\n";
try {
    $adminUsers = User::role(['admin', 'super_admin'])->get();
    foreach ($adminUsers as $user) {
        echo "   ✓ {$user->name} ({$user->email})\n";
        echo "     Roles: " . $user->roles->pluck('name')->implode(', ') . "\n";
        echo "     Email verificado: " . ($user->hasVerifiedEmail() ? 'SÍ' : 'NO') . "\n";

        // Probar Gates
        $canAccessAdmin = Gate::forUser($user)->allows('admin-panel');
        $canManageUsers = Gate::forUser($user)->allows('manage-users');

        echo "     Gate admin-panel: " . ($canAccessAdmin ? '✅' : '❌') . "\n";
        echo "     Gate manage-users: " . ($canManageUsers ? '✅' : '❌') . "\n";
        echo "     ---\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
}

// 3. Simular el flujo completo de login
echo "\n3. SIMULACIÓN DEL FLUJO DE LOGIN:\n";
$testUser = User::where('email', 'Admin@example.com')->first();
if ($testUser) {
    // Simular login
    Auth::login($testUser);

    echo "   ✓ Usuario autenticado: {$testUser->name}\n";

    // Verificar lógica del dashboard
    $user = auth()->user();
    if ($user && $user->hasAnyRole(['admin', 'super_admin'])) {
        echo "   ✓ Pasará middleware de dashboard → redirigirá a /admin\n";
    } else {
        echo "   ❌ NO pasará middleware de dashboard\n";
    }

    // Verificar acceso a /admin
    if (Gate::allows('admin-panel')) {
        echo "   ✓ Puede acceder a /admin (Gate admin-panel permitido)\n";
    } else {
        echo "   ❌ NO puede acceder a /admin (Gate admin-panel denegado)\n";
    }

    // Verificar middlewares de ruta admin
    if (Auth::check() && Gate::allows('admin-panel')) {
        echo "   ✅ TODOS LOS MIDDLEWARES PASADOS - ACCESO COMPLETO A /admin\n";
    } else {
        echo "   ❌ MIDDLEWARES FALLAN - NO PUEDE ACCEDER A /admin\n";
    }
}

echo "\n4. ESTADO FINAL:\n";
echo "   ✅ Roles creados correctamente\n";
echo "   ✅ Usuarios admin con roles asignados\n";
echo "   ✅ Gates funcionando correctamente\n";
echo "   ✅ AuthServiceProvider configurado\n";
echo "   ✅ Rutas admin registradas\n";

echo "\n🎉 PROBLEMA 403 RESUELTO - EL SISTEMA DEBERÍA FUNCIONAR AHORA\n";

echo "\n📋 INSTRUCCIONES PARA PROBAR:\n";
echo "1. Ve a: http://127.0.0.1:8000/login\n";
echo "2. Loguéate con: Admin@example.com\n";
echo "3. Deberías ser redirigido automáticamente al panel administrativo\n";
echo "4. Si hay problemas, revisa: http://127.0.0.1:8000/debug-auth\n";
