<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

// Inicializar la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DEBUG SISTEMA DE ROLES Y PERMISOS ===\n\n";

// Obtener todos los usuarios con roles administrativos
$adminUsers = User::role(['admin', 'super_admin'])->get();

echo "Usuarios con roles administrativos encontrados: " . $adminUsers->count() . "\n\n";

foreach ($adminUsers as $user) {
    echo "Usuario: {$user->name} (ID: {$user->id})\n";
    echo "  Email: {$user->email}\n";
    echo "  Roles: " . $user->roles->pluck('name')->implode(', ') . "\n";

    // Verificar hasAnyRole
    $hasAdminRole = $user->hasAnyRole(['admin', 'super_admin']);
    echo "  hasAnyRole(['admin', 'super_admin']): " . ($hasAdminRole ? 'SÍ' : 'NO') . "\n";

    // Verificar Gate admin-panel
    $canAccessAdminPanel = Gate::forUser($user)->allows('admin-panel');
    echo "  Gate 'admin-panel': " . ($canAccessAdminPanel ? 'PERMITIDO' : 'DENEGADO') . "\n";

    echo "  ---\n";
}

// Verificar si hay usuarios sin roles asignados
$usersWithoutRoles = User::doesntHave('roles')->get();
echo "\nUsuarios sin roles asignados: " . $usersWithoutRoles->count() . "\n";

foreach ($usersWithoutRoles as $user) {
    echo "  - {$user->name} ({$user->email})\n";
}

echo "\n=== VERIFICACIÓN DE ROLES DISPONIBLES ===\n";
$roles = \Spatie\Permission\Models\Role::all();
echo "Roles en el sistema: " . $roles->count() . "\n";
foreach ($roles as $role) {
    echo "  - {$role->name}\n";
}

echo "\n=== FIN DEBUG ===\n";
