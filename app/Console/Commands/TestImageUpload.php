<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class TestImageUpload extends Command
{
    protected $signature = 'test:image-upload';
    protected $description = 'Test image upload configuration';

    public function handle()
    {
        $this->info('=== PRUEBA DE CONFIGURACIÓN DE IMÁGENES ===');
        $this->line('');

        // 1. Verificar directorio de storage
        $storagePath = storage_path('app/livewire-tmp');
        $this->info('1. Directorio livewire-tmp: ' . $storagePath);
        $this->line('   Existe: ' . (is_dir($storagePath) ? '✓ SÍ' : '✗ NO'));
        $this->line('   Escribible: ' . (is_writable($storagePath) ? '✓ SÍ' : '✗ NO'));
        $this->line('');

        // 2. Verificar directorio público
        $publicPath = storage_path('app/public/products');
        $this->info('2. Directorio productos público: ' . $publicPath);
        $this->line('   Existe: ' . (is_dir($publicPath) ? '✓ SÍ' : '✗ NO'));
        $this->line('   Escribible: ' . (is_writable($publicPath) ? '✓ SÍ' : '✗ NO'));
        $this->line('');

        // 3. Verificar enlace simbólico
        $symlinkPath = public_path('storage');
        $this->info('3. Enlace simbólico: ' . $symlinkPath);
        $this->line('   Existe: ' . (is_link($symlinkPath) || is_dir($symlinkPath) ? '✓ SÍ' : '✗ NO'));
        $this->line('');

        // 4. Verificar extensiones de PHP
        $this->info('4. Extensiones de PHP necesarias:');
        $this->line('   GD: ' . (extension_loaded('gd') ? '✓ Instalada' : '✗ NO instalada'));
        $this->line('   Fileinfo: ' . (extension_loaded('fileinfo') ? '✓ Instalada' : '✗ NO instalada'));
        $this->line('   Exif: ' . (extension_loaded('exif') ? '✓ Instalada' : '✗ NO instalada'));
        $this->line('');

        // 5. Verificar configuración de subida
        $this->info('5. Configuración de PHP:');
        $this->line('   upload_max_filesize: ' . ini_get('upload_max_filesize'));
        $this->line('   post_max_size: ' . ini_get('post_max_size'));
        $this->line('   max_file_uploads: ' . ini_get('max_file_uploads'));
        $this->line('   memory_limit: ' . ini_get('memory_limit'));
        $this->line('');

        // 6. Verificar configuración de Livewire
        $livewireConfig = config('livewire.temporary_file_upload');
        $this->info('6. Configuración de Livewire:');
        $this->line('   Disco: ' . $livewireConfig['disk']);
        $this->line('   Directorio: ' . $livewireConfig['directory']);
        $this->line('   MIME types permitidos: ' . implode(', ', $livewireConfig['preview_mimes']));
        $this->line('');

        $this->info('=== FIN DE LA PRUEBA ===');
    }
}
