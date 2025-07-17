<?php
// Archivo de prueba para verificar subida de imágenes
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Storage;

// Verificar configuración
echo "=== PRUEBA DE CONFIGURACIÓN DE IMÁGENES ===\n\n";

// 1. Verificar directorio de storage
$storagePath = storage_path('app/livewire-tmp');
echo "1. Directorio livewire-tmp: " . ($storagePath) . "\n";
echo "   Existe: " . (is_dir($storagePath) ? "✓ SÍ" : "✗ NO") . "\n";
echo "   Escribible: " . (is_writable($storagePath) ? "✓ SÍ" : "✗ NO") . "\n\n";

// 2. Verificar directorio público
$publicPath = storage_path('app/public/products');
echo "2. Directorio productos público: " . $publicPath . "\n";
echo "   Existe: " . (is_dir($publicPath) ? "✓ SÍ" : "✗ NO") . "\n";
echo "   Escribible: " . (is_writable($publicPath) ? "✓ SÍ" : "✗ NO") . "\n\n";

// 3. Verificar enlace simbólico
$symlinkPath = public_path('storage');
echo "3. Enlace simbólico: " . $symlinkPath . "\n";
echo "   Existe: " . (is_link($symlinkPath) || is_dir($symlinkPath) ? "✓ SÍ" : "✗ NO") . "\n\n";

// 4. Verificar extensiones de PHP
echo "4. Extensiones de PHP necesarias:\n";
echo "   GD: " . (extension_loaded('gd') ? "✓ Instalada" : "✗ NO instalada") . "\n";
echo "   Fileinfo: " . (extension_loaded('fileinfo') ? "✓ Instalada" : "✗ NO instalada") . "\n";
echo "   Exif: " . (extension_loaded('exif') ? "✓ Instalada" : "✗ NO instalada") . "\n\n";

// 5. Verificar configuración de subida
echo "5. Configuración de PHP:\n";
echo "   upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
echo "   post_max_size: " . ini_get('post_max_size') . "\n";
echo "   max_file_uploads: " . ini_get('max_file_uploads') . "\n";
echo "   memory_limit: " . ini_get('memory_limit') . "\n\n";

echo "=== FIN DE LA PRUEBA ===\n";
