<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Gate;

// Test script para verificar el sistema de usuarios y roles

echo "=== SISTEMA DE USUARIOS CON ROLES - TEST ===\n\n";

// 1. Verificar que los roles existen
echo "1. Verificando roles en la base de datos:\n";
$roles = \Spatie\Permission\Models\Role::all();
foreach ($roles as $role) {
    echo "   - {$role->name}\n";
}
echo "\n";

// 2. Verificar usuarios con roles
echo "2. Verificando usuarios y sus roles:\n";
$users = User::with('roles')->get();
foreach ($users as $user) {
    $roleName = $user->roles->first()->name ?? 'Sin rol';
    echo "   - {$user->name} ({$user->email}) -> {$roleName}\n";
}
echo "\n";

// 3. Verificar enums de roles
echo "3. Verificando enum UserRole:\n";
foreach (['cliente', 'admin', 'super_admin'] as $roleName) {
    try {
        $roleEnum = UserRole::from($roleName);
        echo "   - {$roleName}: {$roleEnum->label()}\n";
    } catch (Exception $e) {
        echo "   - ERROR en {$roleName}: {$e->getMessage()}\n";
    }
}
echo "\n";

// 4. Verificar gates
echo "4. Verificando gates de autorización:\n";
$adminUser = User::whereHas('roles', function($query) {
    $query->where('name', 'admin');
})->first();

$superAdminUser = User::whereHas('roles', function($query) {
    $query->where('name', 'super_admin');
})->first();

if ($adminUser) {
    echo "   - Usuario admin ({$adminUser->email}):\n";
    echo "     - Puede acceder al admin: " . (Gate::forUser($adminUser)->allows('admin-panel') ? 'SÍ' : 'NO') . "\n";
    echo "     - Puede gestionar usuarios: " . (Gate::forUser($adminUser)->allows('manage-users') ? 'SÍ' : 'NO') . "\n";
}

if ($superAdminUser) {
    echo "   - Usuario super_admin ({$superAdminUser->email}):\n";
    echo "     - Puede acceder al admin: " . (Gate::forUser($superAdminUser)->allows('admin-panel') ? 'SÍ' : 'NO') . "\n";
    echo "     - Puede gestionar usuarios: " . (Gate::forUser($superAdminUser)->allows('manage-users') ? 'SÍ' : 'NO') . "\n";
}
echo "\n";

// 5. Verificar que no hay usuarios duplicados por email
echo "5. Verificando duplicados de email:\n";
$duplicates = User::select('email')
    ->groupBy('email')
    ->havingRaw('count(*) > 1')
    ->get();

if ($duplicates->count() > 0) {
    echo "   ⚠️ ENCONTRADOS EMAILS DUPLICADOS:\n";
    foreach ($duplicates as $duplicate) {
        echo "     - {$duplicate->email}\n";
    }
} else {
    echo "   ✅ No hay emails duplicados\n";
}
echo "\n";

echo "=== FIN DEL TEST ===\n";
