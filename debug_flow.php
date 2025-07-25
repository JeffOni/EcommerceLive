<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Inicializar la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "=== SIMULACIÓN COMPLETA DEL FLUJO DE AUTENTICACIÓN ===\n\n";

// Obtener un usuario admin
$adminUser = User::role(['admin', 'super_admin'])->first();

if (!$adminUser) {
    echo "ERROR: No se encontró ningún usuario admin\n";
    exit;
}

echo "👤 Usuario de prueba: {$adminUser->name} ({$adminUser->email})\n";
echo "🏷️  Roles: " . $adminUser->roles->pluck('name')->implode(', ') . "\n\n";

// Simular autenticación
Auth::login($adminUser);

echo "=== PASO 1: VERIFICACIÓN DE AUTENTICACIÓN ===\n";
echo "✅ Usuario autenticado: " . (Auth::check() ? 'SÍ' : 'NO') . "\n";
echo "👤 Usuario actual: " . (Auth::user() ? Auth::user()->name : 'NINGUNO') . "\n\n";

echo "=== PASO 2: LÓGICA DEL DASHBOARD ===\n";
$user = auth()->user();
if ($user && $user->hasAnyRole(['admin', 'super_admin'])) {
    echo "✅ CONDICIÓN CUMPLIDA: Usuario tiene rol administrativo\n";
    echo "🔄 ACCIÓN: Debería redirigir a '/admin'\n";
    $shouldRedirectTo = '/admin';
} else {
    echo "❌ CONDICIÓN NO CUMPLIDA: Usuario NO tiene rol administrativo\n";
    echo "🔄 ACCIÓN: Debería redirigir a 'welcome.index'\n";
    $shouldRedirectTo = 'welcome.index';
}
echo "\n";

echo "=== PASO 3: VERIFICACIÓN DE ACCESO A /admin ===\n";

// Verificar middleware auth
$isAuthenticated = Auth::check();
echo "🔐 Middleware 'auth': " . ($isAuthenticated ? '✅ PASA' : '❌ FALLA') . "\n";

// Verificar Gate admin-panel
$canAccessAdminPanel = Gate::allows('admin-panel');
echo "🛡️  Gate 'admin-panel': " . ($canAccessAdminPanel ? '✅ PASA' : '❌ FALLA') . "\n";

// Verificar acceso combinado
$canAccessAdmin = $isAuthenticated && $canAccessAdminPanel;
echo "🎯 ACCESO FINAL A /admin: " . ($canAccessAdmin ? '✅ PERMITIDO' : '❌ DENEGADO') . "\n\n";

echo "=== PASO 4: DIAGNÓSTICO DETALLADO ===\n";

// Verificar cada rol individualmente
echo "🔍 Verificación detallada de roles:\n";
echo "   - hasRole('admin'): " . ($user->hasRole('admin') ? '✅' : '❌') . "\n";
echo "   - hasRole('super_admin'): " . ($user->hasRole('super_admin') ? '✅' : '❌') . "\n";
echo "   - hasAnyRole(['admin', 'super_admin']): " . ($user->hasAnyRole(['admin', 'super_admin']) ? '✅' : '❌') . "\n";

// Verificar la ruta específica existe
try {
    $adminRoute = Route::getRoutes()->getByName('admin.dashboard');
    echo "🛣️  Ruta 'admin.dashboard': " . ($adminRoute ? '✅ EXISTE' : '❌ NO EXISTE') . "\n";
    if ($adminRoute) {
        echo "   URI: " . $adminRoute->uri() . "\n";
        echo "   Middlewares: " . implode(', ', $adminRoute->middleware()) . "\n";
    }
} catch (Exception $e) {
    echo "🛣️  Ruta 'admin.dashboard': ❌ ERROR - " . $e->getMessage() . "\n";
}

echo "\n=== CONCLUSIÓN ===\n";
if ($canAccessAdmin) {
    echo "✅ El usuario DEBERÍA poder acceder a /admin sin problemas\n";
} else {
    echo "❌ PROBLEMA DETECTADO: El usuario NO puede acceder a /admin\n";
    if (!$isAuthenticated) {
        echo "   - Problema: No está autenticado\n";
    }
    if (!$canAccessAdminPanel) {
        echo "   - Problema: Gate 'admin-panel' deniega el acceso\n";
    }
}

echo "\n=== FIN SIMULACIÓN ===\n";
