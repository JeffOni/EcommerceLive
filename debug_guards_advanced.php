<?php

require_once 'vendor/autoload.php';

// Inicializar la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DIAGNÓSTICO AVANZADO - GUARDS Y SESIONES ===\n\n";

// 1. Verificar configuración de guards
echo "1. CONFIGURACIÓN DE GUARDS:\n";
$authConfig = config('auth');
echo "   Default guard: " . $authConfig['defaults']['guard'] . "\n";
echo "   Default provider: " . $authConfig['defaults']['passwords'] . "\n";

$webGuard = $authConfig['guards']['web'] ?? null;
if ($webGuard) {
    echo "   Web guard driver: " . $webGuard['driver'] . "\n";
    echo "   Web guard provider: " . $webGuard['provider'] . "\n";
} else {
    echo "   ❌ Web guard no configurado\n";
}

$sanctumGuard = $authConfig['guards']['sanctum'] ?? null;
if ($sanctumGuard) {
    echo "   Sanctum guard driver: " . $sanctumGuard['driver'] . "\n";
    echo "   Sanctum guard provider: " . $sanctumGuard['provider'] . "\n";
} else {
    echo "   ❌ Sanctum guard no configurado\n";
}

// 2. Verificar configuración de Jetstream
echo "\n2. CONFIGURACIÓN DE JETSTREAM:\n";
$jetstreamConfig = config('jetstream');
echo "   Jetstream guard: " . $jetstreamConfig['guard'] . "\n";
echo "   Auth session middleware: " . $jetstreamConfig['auth_session'] . "\n";

// 3. Verificar configuración de Fortify
echo "\n3. CONFIGURACIÓN DE FORTIFY:\n";
$fortifyConfig = config('fortify');
echo "   Fortify guard: " . $fortifyConfig['guard'] . "\n";
echo "   Fortify home: " . $fortifyConfig['home'] . "\n";
echo "   Username field: " . $fortifyConfig['username'] . "\n";

// 4. Verificar tabla de roles
echo "\n4. TABLA DE ROLES Y PERMISOS:\n";
try {
    $rolesCount = \Spatie\Permission\Models\Role::count();
    $permissionsCount = \Spatie\Permission\Models\Permission::count();
    echo "   Roles en DB: {$rolesCount}\n";
    echo "   Permisos en DB: {$permissionsCount}\n";

    $roles = \Spatie\Permission\Models\Role::all();
    foreach ($roles as $role) {
        echo "   - Rol: {$role->name} (guard: {$role->guard_name})\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error accediendo a roles: " . $e->getMessage() . "\n";
}

// 5. Verificar configuración de sesiones
echo "\n5. CONFIGURACIÓN DE SESIONES:\n";
$sessionConfig = config('session');
echo "   Driver: " . $sessionConfig['driver'] . "\n";
echo "   Lifetime: " . $sessionConfig['lifetime'] . " minutos\n";
echo "   Cookie name: " . $sessionConfig['cookie'] . "\n";
echo "   Cookie domain: " . ($sessionConfig['domain'] ?? 'null') . "\n";
echo "   Secure cookie: " . ($sessionConfig['secure'] ? 'true' : 'false') . "\n";
echo "   HttpOnly: " . ($sessionConfig['http_only'] ? 'true' : 'false') . "\n";
echo "   Same site: " . ($sessionConfig['same_site'] ?? 'null') . "\n";

// 6. Verificar middlewares de rutas admin
echo "\n6. MIDDLEWARES DE RUTAS ADMIN:\n";
try {
    $adminRoute = \Illuminate\Support\Facades\Route::getRoutes()->getByName('admin.dashboard');
    if ($adminRoute) {
        echo "   Ruta: " . $adminRoute->uri() . "\n";
        echo "   Middlewares: " . implode(', ', $adminRoute->middleware()) . "\n";
        echo "   Action: " . $adminRoute->getActionName() . "\n";
    } else {
        echo "   ❌ Ruta admin.dashboard no encontrada\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error verificando rutas: " . $e->getMessage() . "\n";
}

// 7. Verificar si hay conflictos de middleware
echo "\n7. ANÁLISIS DE MIDDLEWARES:\n";
$webMiddlewares = $authConfig['guards']['web']['middleware'] ?? [];
$sanctumMiddlewares = $authConfig['guards']['sanctum']['middleware'] ?? [];
echo "   Web middlewares: " . implode(', ', $webMiddlewares) . "\n";
echo "   Sanctum middlewares: " . implode(', ', $sanctumMiddlewares) . "\n";

// 8. Verificar el problema específico
echo "\n8. DIAGNÓSTICO DEL PROBLEMA 403:\n";

$testUser = \App\Models\User::where('email', 'Admin@example.com')->first();
if ($testUser) {
    echo "   Usuario de prueba encontrado: {$testUser->name}\n";

    // Simular autenticación con diferentes guards
    echo "   Probando con guard 'web':\n";
    \Illuminate\Support\Facades\Auth::guard('web')->login($testUser);
    echo "     - Autenticado con web: " . (\Illuminate\Support\Facades\Auth::guard('web')->check() ? 'SÍ' : 'NO') . "\n";
    echo "     - Gate admin-panel: " . (\Illuminate\Support\Facades\Gate::allows('admin-panel') ? 'SÍ' : 'NO') . "\n";

    echo "   Probando con guard 'sanctum':\n";
    \Illuminate\Support\Facades\Auth::guard('sanctum')->login($testUser);
    echo "     - Autenticado con sanctum: " . (\Illuminate\Support\Facades\Auth::guard('sanctum')->check() ? 'SÍ' : 'NO') . "\n";

    // Verificar roles con ambos guards
    echo "   Verificando roles:\n";
    echo "     - hasAnyRole (web): " . ($testUser->hasAnyRole(['admin', 'super_admin'], 'web') ? 'SÍ' : 'NO') . "\n";
    try {
        echo "     - hasAnyRole (sanctum): " . ($testUser->hasAnyRole(['admin', 'super_admin'], 'sanctum') ? 'SÍ' : 'NO') . "\n";
    } catch (Exception $e) {
        echo "     - hasAnyRole (sanctum): ERROR - " . $e->getMessage() . "\n";
    }
}

echo "\n=== POSIBLES CAUSAS DEL PROBLEMA ===\n";
echo "1. Conflicto entre guards 'web' y 'sanctum'\n";
echo "2. Roles creados para guard incorrecto\n";
echo "3. Middleware de autenticación usando guard diferente\n";
echo "4. Problema con sesiones o cookies\n";
echo "5. Cache no limpiado correctamente\n";

echo "\n=== FIN DIAGNÓSTICO AVANZADO ===\n";
