<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

// Inicializar la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== PRUEBA FINAL ANTES DE TESTING EN NAVEGADOR ===\n\n";

$user = User::where('email', 'Admin@example.com')->first();

if ($user) {
    echo "1. DATOS DEL USUARIO:\n";
    echo "   Nombre: {$user->name}\n";
    echo "   Email: {$user->email}\n";
    echo "   Email verificado: " . ($user->hasVerifiedEmail() ? 'SÍ' : 'NO') . "\n";
    echo "   Roles: " . $user->roles->pluck('name')->implode(', ') . "\n";

    echo "\n2. SIMULACIÓN DE AUTENTICACIÓN CON SANCTUM:\n";
    Auth::guard('sanctum')->setUser($user);
    echo "   Autenticado con sanctum: " . (Auth::guard('sanctum')->check() ? 'SÍ' : 'NO') . "\n";

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
    $passAuth = Auth::guard('sanctum')->check();
    $passGate = Gate::forUser($user)->allows('admin-panel');

    echo "   Middleware 'auth': " . ($passAuth ? '✅' : '❌') . "\n";
    echo "   Middleware 'can:admin-panel': " . ($passGate ? '✅' : '❌') . "\n";

    if ($passAuth && $passGate) {
        echo "   ✅ PUEDE ACCEDER A /admin\n";
    } else {
        echo "   ❌ NO PUEDE ACCEDER A /admin\n";
    }

    echo "\n🎯 RESULTADO FINAL:\n";
    if ($hasAdminRole && $passAuth && $passGate) {
        echo "✅ TODO FUNCIONA - EL USUARIO DEBERÍA PODER:\n";
        echo "   1. Loguearse correctamente\n";
        echo "   2. Ser redirigido al dashboard admin\n";
        echo "   3. Acceder a /admin sin problemas\n";
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

echo "\n📋 INSTRUCCIONES PARA PROBAR:\n";
echo "1. Abre el navegador y ve a: http://127.0.0.1:8000/debug-auth\n";
echo "2. Verifica que muestre 'authenticated': false\n";
echo "3. Ve a: http://127.0.0.1:8000/login\n";
echo "4. Loguéate con: Admin@example.com\n";
echo "5. Inmediatamente después del login, ve a: http://127.0.0.1:8000/debug-auth\n";
echo "6. Debería mostrar datos del usuario autenticado\n";
echo "7. Luego ve a: http://127.0.0.1:8000/admin\n";
echo "8. Debería funcionar sin error 403\n";

echo "\n=== FIN PRUEBA FINAL ===\n";
