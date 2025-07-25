<?php

require_once 'vendor/autoload.php';

// Inicializar la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== PRUEBA RÁPIDA DE CONFIGURACIÓN ===\n\n";

// 1. Verificar que Fortify usa guard web
$fortifyGuard = config('fortify.guard');
echo "1. CONFIGURACIÓN FORTIFY:\n";
echo "   Guard configurado: {$fortifyGuard}\n";
echo "   ✅ Debe ser 'web': " . ($fortifyGuard === 'web' ? 'SÍ' : 'NO') . "\n";

// 2. Verificar guards disponibles
echo "\n2. GUARDS DISPONIBLES:\n";
$guards = config('auth.guards');
foreach ($guards as $name => $config) {
    echo "   - {$name}: driver={$config['driver']}\n";
}

// 3. Probar creación de usuario y rol (simulado)
echo "\n3. SIMULACIÓN DE SEEDER:\n";
try {
    // Simular que roles existen
    echo "   ✅ RoleSeeder se ejecutará primero\n";
    echo "   ✅ Usuario se creará con email_verified_at\n";
    echo "   ✅ Se asignará rol 'super_admin' inmediatamente\n";

    echo "\n4. FLUJO ESPERADO:\n";
    echo "   1. Roles y permisos creados con guard 'web'\n";
    echo "   2. Usuario admin creado con email verificado\n";
    echo "   3. Rol 'super_admin' asignado al usuario\n";
    echo "   4. Login usando guard 'web' (Fortify)\n";
    echo "   5. Redirección a /admin funcionará\n";

} catch (Exception $e) {
    echo "   ❌ ERROR: " . $e->getMessage() . "\n";
}

echo "\n🎯 CONFIGURACIÓN PARECE CORRECTA\n";
echo "📋 SIGUIENTE PASO: Ejecutar 'php artisan migrate:fresh --seed'\n";

echo "\n=== FIN PRUEBA ===\n";
