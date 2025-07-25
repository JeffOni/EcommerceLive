<?php

require_once 'vendor/autoload.php';

use App\Models\User;

// Inicializar la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CORRECCIÓN DE EMAILS DE USUARIOS ADMIN ===\n\n";

// Obtener usuarios admin sin email verificado
$adminUsers = User::role(['admin', 'super_admin'])->whereNull('email_verified_at')->get();

echo "Usuarios admin sin email verificado: " . $adminUsers->count() . "\n\n";

foreach ($adminUsers as $user) {
    echo "Verificando email para: {$user->name} ({$user->email})\n";

    // Marcar email como verificado
    $user->markEmailAsVerified();

    echo "✅ Email verificado para {$user->name}\n";
    echo "---\n";
}

// Verificar todos los usuarios admin nuevamente
echo "\n=== VERIFICACIÓN FINAL ===\n";
$allAdminUsers = User::role(['admin', 'super_admin'])->get();

foreach ($allAdminUsers as $user) {
    echo "Usuario: {$user->name} ({$user->email})\n";
    echo "  Email verificado: " . ($user->hasVerifiedEmail() ? '✅ SÍ' : '❌ NO') . "\n";
    echo "  Roles: " . $user->roles->pluck('name')->implode(', ') . "\n";
    echo "---\n";
}

echo "\n✅ CORRECCIÓN COMPLETADA\n";
