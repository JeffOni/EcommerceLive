<?php

require_once 'vendor/autoload.php';

// Inicializar la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== VERIFICACIÓN DE CONFIGURACIÓN DE REDIRECCIÓN ===\n\n";

// Verificar configuración de Fortify
$fortifyHome = config('fortify.home');
echo "📍 Configuración Fortify 'home': {$fortifyHome}\n";

// Verificar que la ruta dashboard existe
$dashboardRoute = \Illuminate\Support\Facades\Route::getRoutes()->getByName('dashboard');
echo "🛣️  Ruta 'dashboard': " . ($dashboardRoute ? '✅ EXISTE' : '❌ NO EXISTE') . "\n";

if ($dashboardRoute) {
    echo "   URI: /" . $dashboardRoute->uri() . "\n";
    echo "   Middlewares: " . implode(', ', $dashboardRoute->middleware()) . "\n";
}

// Verificar ruta admin
$adminRoute = \Illuminate\Support\Facades\Route::getRoutes()->getByName('admin.dashboard');
echo "🛣️  Ruta 'admin.dashboard': " . ($adminRoute ? '✅ EXISTE' : '❌ NO EXISTE') . "\n";

if ($adminRoute) {
    echo "   URI: /" . $adminRoute->uri() . "\n";
    echo "   Middlewares: " . implode(', ', $adminRoute->middleware()) . "\n";
}

echo "\n=== FLUJO ESPERADO DESPUÉS DEL LOGIN ===\n";
echo "1. Usuario se loguea → Fortify redirige a: {$fortifyHome}\n";
echo "2. Dashboard verifica roles:\n";
echo "   - Si es admin/super_admin → redirige a '/admin'\n";
echo "   - Si es cliente → redirige a 'welcome.index'\n";

echo "\n=== FIN VERIFICACIÓN ===\n";
