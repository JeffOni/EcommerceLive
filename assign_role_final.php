<?php

require_once 'vendor/autoload.php';

use App\Models\User;

// Inicializar la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== ASIGNACIÓN FINAL DE ROLES ===\n\n";

$user = User::where('email', 'Admin@example.com')->first();

if ($user) {
    echo "Usuario encontrado: {$user->name}\n";

    // Limpiar roles existentes
    $user->roles()->detach();
    echo "Roles anteriores eliminados\n";

    // Asignar nuevo rol
    $user->assignRole('super_admin');
    echo "Rol 'super_admin' asignado\n";

    // Verificar
    $user->refresh();
    $roles = $user->roles;
    echo "Roles actuales: " . $roles->pluck('name')->implode(', ') . "\n";
    echo "Guards: " . $roles->pluck('guard_name')->implode(', ') . "\n";

    // Probar hasAnyRole
    $hasRole = $user->hasAnyRole(['admin', 'super_admin']);
    echo "hasAnyRole funcionando: " . ($hasRole ? '✅ SÍ' : '❌ NO') . "\n";

    if ($hasRole) {
        echo "✅ USUARIO LISTO PARA USAR\n";
    } else {
        echo "❌ PROBLEMA PERSISTE\n";
    }
} else {
    echo "Usuario no encontrado\n";
}

echo "\n=== FIN ===\n";
