<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

// Inicializar la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DIAGNÓSTICO COMPLETO DEL PROBLEMA 403 ===\n\n";

// 1. Verificar usuarios admin
echo "1. VERIFICACIÓN DE USUARIOS ADMIN:\n";
$adminUsers = User::role(['admin', 'super_admin'])->get();
foreach ($adminUsers as $user) {
    echo "   Usuario: {$user->name} ({$user->email})\n";
    echo "   Roles: " . $user->roles->pluck('name')->implode(', ') . "\n";
    echo "   Email verificado: " . ($user->hasVerifiedEmail() ? 'SÍ' : 'NO') . "\n";
    echo "   ---\n";
}

// 2. Verificar Gates definidos
echo "\n2. VERIFICACIÓN DE GATES:\n";
$gates = [
    'admin-panel' => 'Acceso al panel administrativo',
    'manage-users' => 'Gestión de usuarios',
    'verify-payments' => 'Verificación de pagos'
];

$testUser = $adminUsers->first();
if ($testUser) {
    echo "   Probando con usuario: {$testUser->name}\n";
    foreach ($gates as $gate => $description) {
        $allowed = Gate::forUser($testUser)->allows($gate);
        echo "   - {$gate}: " . ($allowed ? '✅ PERMITIDO' : '❌ DENEGADO') . "\n";
    }
}

// 3. Verificar rutas admin
echo "\n3. VERIFICACIÓN DE RUTAS ADMIN:\n";
try {
    $adminRoutes = Route::getRoutes()->getByName('admin.dashboard');
    if ($adminRoutes) {
        echo "   ✅ Ruta admin.dashboard existe\n";
        echo "   URI: /{$adminRoutes->uri()}\n";
        echo "   Middlewares: " . implode(', ', $adminRoutes->middleware()) . "\n";
    } else {
        echo "   ❌ Ruta admin.dashboard NO existe\n";
    }
} catch (Exception $e) {
    echo "   ❌ Error verificando rutas: " . $e->getMessage() . "\n";
}

// 4. Simular autenticación y verificar acceso
echo "\n4. SIMULACIÓN DE AUTENTICACIÓN:\n";
if ($testUser) {
    Auth::login($testUser);
    echo "   Usuario autenticado: " . (Auth::check() ? 'SÍ' : 'NO') . "\n";
    echo "   Usuario actual: " . (Auth::user() ? Auth::user()->name : 'NINGUNO') . "\n";

    // Verificar cada middleware individualmente
    echo "\n   Verificación de middlewares:\n";
    echo "   - auth: " . (Auth::check() ? '✅' : '❌') . "\n";
    echo "   - can:admin-panel: " . (Gate::allows('admin-panel') ? '✅' : '❌') . "\n";

    // Verificar si puede acceder a la lógica del dashboard
    $user = auth()->user();
    $shouldRedirectToAdmin = $user && $user->hasAnyRole(['admin', 'super_admin']);
    echo "   - Dashboard lógica (hasAnyRole): " . ($shouldRedirectToAdmin ? '✅' : '❌') . "\n";
}

// 5. Verificar AuthServiceProvider
echo "\n5. VERIFICACIÓN DEL AUTHSERVICEPROVIDER:\n";
try {
    $authProvider = app(\App\Providers\AuthServiceProvider::class);
    echo "   ✅ AuthServiceProvider cargado correctamente\n";

    // Verificar que los gates están registrados
    $definedGates = Gate::abilities();
    foreach ($gates as $gateName => $description) {
        if (isset($definedGates[$gateName])) {
            echo "   ✅ Gate '{$gateName}' está registrado\n";
        } else {
            echo "   ❌ Gate '{$gateName}' NO está registrado\n";
        }
    }
} catch (Exception $e) {
    echo "   ❌ Error con AuthServiceProvider: " . $e->getMessage() . "\n";
}

// 6. Verificar configuración de Jetstream y Fortify
echo "\n6. VERIFICACIÓN DE CONFIGURACIÓN:\n";
echo "   APP_ENV: " . config('app.env') . "\n";
echo "   Fortify home: " . config('fortify.home') . "\n";
echo "   Jetstream guard: " . config('jetstream.guard') . "\n";
echo "   Session driver: " . config('session.driver') . "\n";

// 7. Verificar permisos de Spatie
echo "\n7. VERIFICACIÓN DE SPATIE PERMISSIONS:\n";
try {
    $roles = \Spatie\Permission\Models\Role::all();
    echo "   Roles disponibles: " . $roles->pluck('name')->implode(', ') . "\n";

    $permissions = \Spatie\Permission\Models\Permission::all();
    echo "   Permisos disponibles: " . $permissions->count() . " permisos\n";

    if ($testUser) {
        echo "   Permisos del usuario de prueba:\n";
        $userPermissions = $testUser->getAllPermissions();
        if ($userPermissions->count() > 0) {
            foreach ($userPermissions as $permission) {
                echo "     - {$permission->name}\n";
            }
        } else {
            echo "     - Sin permisos directos (solo roles)\n";
        }
    }
} catch (Exception $e) {
    echo "   ❌ Error con Spatie Permissions: " . $e->getMessage() . "\n";
}

echo "\n=== FIN DIAGNÓSTICO ===\n";
