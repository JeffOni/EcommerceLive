<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

// Inicializar la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST DE AUTENTICACIÓN Y REDIRECCIÓN ===\n\n";

// Obtener un usuario admin
$adminUser = User::role(['admin', 'super_admin'])->first();

if (!$adminUser) {
    echo "ERROR: No se encontró ningún usuario admin\n";
    exit;
}

echo "Probando con usuario: {$adminUser->name} ({$adminUser->email})\n";
echo "Roles del usuario: " . $adminUser->roles->pluck('name')->implode(', ') . "\n\n";

// Simular autenticación
Auth::login($adminUser);

echo "Usuario autenticado: " . (Auth::check() ? 'SÍ' : 'NO') . "\n";
echo "Usuario actual: " . (Auth::user() ? Auth::user()->name : 'NINGUNO') . "\n\n";

// Verificar condiciones del dashboard
$user = auth()->user();
if ($user && $user->hasAnyRole(['admin', 'super_admin', 'empleado'])) {
    echo "✅ CONDICIÓN CUMPLIDA: Usuario tiene rol administrativo\n";
    echo "   Debería redirigir a: /admin\n";
} else {
    echo "❌ CONDICIÓN NO CUMPLIDA: Usuario NO tiene rol administrativo\n";
    echo "   Debería redirigir a: welcome.index\n";
}

// Verificar Gate admin-panel
$canAccessAdmin = Gate::allows('admin-panel');
echo "\nPuede acceder al admin panel: " . ($canAccessAdmin ? 'SÍ' : 'NO') . "\n";

// Verificar middleware
echo "\nVerificando middlewares aplicados a rutas admin:\n";
echo "- auth: " . (Auth::check() ? '✅ PASADO' : '❌ FALLA') . "\n";
echo "- can:admin-panel: " . (Gate::allows('admin-panel') ? '✅ PASADO' : '❌ FALLA') . "\n";

echo "\n=== FIN TEST ===\n";
