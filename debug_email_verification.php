<?php

require_once 'vendor/autoload.php';

use App\Models\User;

// Inicializar la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== VERIFICACIÓN DE EMAIL VERIFICADO ===\n\n";

// Obtener usuarios admin
$adminUsers = User::role(['admin', 'super_admin'])->get();

foreach ($adminUsers as $user) {
    echo "Usuario: {$user->name} ({$user->email})\n";
    echo "  Email verificado: " . ($user->hasVerifiedEmail() ? '✅ SÍ' : '❌ NO') . "\n";
    echo "  email_verified_at: " . ($user->email_verified_at ?? 'NULL') . "\n";
    echo "  ---\n";
}

// Verificar si hay algún problema con la verificación de email
echo "\n=== VERIFICACIÓN COMPLETA DEL FLUJO ===\n";

$testUser = $adminUsers->first();
if ($testUser) {
    echo "Probando con: {$testUser->name}\n";
    echo "1. ¿Está autenticado? → Se simula después del login\n";
    echo "2. ¿Email verificado? → " . ($testUser->hasVerifiedEmail() ? '✅ SÍ' : '❌ NO') . "\n";
    echo "3. ¿Tiene rol admin? → " . ($testUser->hasAnyRole(['admin', 'super_admin']) ? '✅ SÍ' : '❌ NO') . "\n";
    echo "4. ¿Puede acceder a admin-panel? → " . (\Illuminate\Support\Facades\Gate::forUser($testUser)->allows('admin-panel') ? '✅ SÍ' : '❌ NO') . "\n";

    $canAccessDashboard = $testUser->hasVerifiedEmail() && $testUser->hasAnyRole(['admin', 'super_admin']);
    echo "\n✅ PUEDE ACCEDER AL DASHBOARD Y SER REDIRIGIDO: " . ($canAccessDashboard ? 'SÍ' : 'NO') . "\n";
}

echo "\n=== FIN VERIFICACIÓN ===\n";
