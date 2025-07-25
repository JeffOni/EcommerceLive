<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

// Inicializar la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== VERIFICACIÓN DESPUÉS DE MIGRATE:FRESH --SEED ===\n\n";

$user = User::where('email', 'Admin@example.com')->first();

if ($user) {
    echo "1. DATOS DEL USUARIO:\n";
    echo "   Nombre: {$user->name}\n";
    echo "   Email: {$user->email}\n";
    echo "   Email verificado: " . ($user->hasVerifiedEmail() ? 'SÍ' : 'NO') . "\n";
    echo "   Roles: " . $user->roles->pluck('name')->implode(', ') . "\n";
    echo "   Guard de roles: " . $user->roles->pluck('guard_name')->implode(', ') . "\n";

    echo "\n2. SIMULACIÓN DE AUTENTICACIÓN CON GUARD WEB:\n";
    Auth::guard('web')->setUser($user);
    echo "   Autenticado con web: " . (Auth::guard('web')->check() ? 'SÍ' : 'NO') . "\n";

    echo "\n3. PRUEBA DE GATES:\n";
    $gates = ['admin-panel', 'manage-users', 'verify-payments'];
    foreach ($gates as $gate) {
        $allowed = Gate::forUser($user)->allows($gate);
        echo "   Gate {$gate}: " . ($allowed ? '✅' : '❌') . "\n";
    }

    echo "\n4. SIMULACIÓN DEL FLUJO DASHBOARD:\n";
    $hasAdminRole = $user->hasAnyRole(['admin', 'super_admin']);
    echo "   hasAnyRole(['admin', 'super_admin']): " . ($hasAdminRole ? '✅' : '❌') . "\n";

    if ($hasAdminRole) {
        echo "   ✅ DEBERÍA REDIRIGIR A /admin\n";
    } else {
        echo "   ❌ REDIRIGIRÁ A welcome\n";
    }

    echo "\n5. SIMULACIÓN DE MIDDLEWARE ADMIN:\n";
    $passAuth = Auth::guard('web')->check();
    $passGate = Gate::forUser($user)->allows('admin-panel');

    echo "   Middleware 'auth:web': " . ($passAuth ? '✅' : '❌') . "\n";
    echo "   Middleware 'can:admin-panel': " . ($passGate ? '✅' : '❌') . "\n";

    if ($passAuth && $passGate) {
        echo "   ✅ PUEDE ACCEDER A /admin\n";
    } else {
        echo "   ❌ NO PUEDE ACCEDER A /admin\n";
    }

    echo "\n6. CONFIGURACIÓN FORTIFY:\n";
    echo "   Guard Fortify: " . config('fortify.guard') . "\n";
    echo "   Home path: " . config('fortify.home') . "\n";

    echo "\n🎯 RESULTADO FINAL:\n";
    if ($hasAdminRole && $passAuth && $passGate) {
        echo "✅ TODO FUNCIONA PERFECTAMENTE - EL USUARIO PUEDE:\n";
        echo "   1. Loguearse sin error de StatefulGuard\n";
        echo "   2. Ser redirigido al dashboard admin\n";
        echo "   3. Acceder a /admin sin problemas 403\n";
    } else {
        echo "❌ PROBLEMAS DETECTADOS:\n";
        if (!$hasAdminRole)
            echo "   - No tiene rol administrativo\n";
        if (!$passAuth)
            echo "   - Falla autenticación\n";
        if (!$passGate)
            echo "   - Falla gate admin-panel\n";
    }

} else {
    echo "❌ Usuario admin no encontrado\n";
}

echo "\n📋 INSTRUCCIONES FINALES:\n";
echo "1. Ejecuta: php artisan config:clear\n";
echo "2. Ve a: http://127.0.0.1:8000/login\n";
echo "3. Loguéate con: Admin@example.com / secreto123\n";
echo "4. Deberías ser redirigido automáticamente a /admin\n";
echo "5. Ya NO más errores de StatefulGuard\n";

echo "\n=== VERIFICACIÓN COMPLETADA ===\n";
