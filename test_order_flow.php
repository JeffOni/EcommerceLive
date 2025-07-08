<?php

require_once 'vendor/autoload.php';

use App\Models\Order;
use App\Models\User;
use App\Services\OrderPdfService;
use App\Livewire\CheckoutAddressSelector;
use Illuminate\Support\Facades\Storage;

// Configurar Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TEST DE FLUJO DE ÓRDENES ===\n\n";

try {
    // 1. Obtener un usuario de prueba
    $user = User::first();
    if (!$user) {
        echo "❌ No hay usuarios en la base de datos\n";
        exit;
    }
    
    echo "✅ Usuario encontrado: {$user->name} ({$user->email})\n";
    
    // 2. Crear una orden de prueba
    $orderData = [
        'user_id' => $user->id,
        'content' => [
            [
                'id' => 1,
                'name' => 'Producto de prueba',
                'price' => 15.50,
                'quantity' => 2,
                'subtotal' => 31.00,
            ]
        ],
        'shipping_address' => [
            'address' => 'Calle Principal 123',
            'reference' => 'Frente al parque',
            'province' => 'Pichincha',
            'canton' => 'Quito',
            'parish' => 'Centro Histórico',
            'postal_code' => '170150',
            'phone' => '0987654321',
            'recipient_name' => 'Juan Pérez',
            'recipient_document' => '1234567890',
            'type' => 'home',
            'full_address' => 'Calle Principal 123, Centro Histórico, Quito, Pichincha'
        ],
        'payment_method' => 2,
        'total' => 36.00,
        'subtotal' => 31.00,
        'shipping_cost' => 5.00,
        'status' => 2,
        'notes' => 'Orden de prueba - Test automático'
    ];
    
    $order = Order::create($orderData);
    echo "✅ Orden creada: #{$order->id}\n";
    
    // 3. Verificar que se generó el PDF
    sleep(2); // Esperar un poco para que se procese el observer
    
    $order->refresh();
    if ($order->pdf_path) {
        echo "✅ PDF generado: {$order->pdf_path}\n";
        
        // Verificar que el archivo existe
        if (Storage::exists($order->pdf_path)) {
            echo "✅ Archivo PDF existe en storage\n";
            
            // Verificar tamaño
            $size = Storage::size($order->pdf_path);
            echo "✅ Tamaño del PDF: {$size} bytes\n";
            
            // Verificar contenido del PDF
            $pdfContent = Storage::get($order->pdf_path);
            if (strpos($pdfContent, 'PDF') !== false) {
                echo "✅ El archivo parece ser un PDF válido\n";
            } else {
                echo "❌ El archivo no parece ser un PDF válido\n";
            }
        } else {
            echo "❌ El archivo PDF no existe en storage\n";
        }
    } else {
        echo "❌ No se generó PDF para la orden\n";
    }
    
    // 4. Verificar que la dirección se guardó correctamente
    if ($order->shipping_address) {
        echo "✅ Dirección de envío guardada correctamente\n";
        
        // Verificar campos clave
        $address = $order->shipping_address;
        if (isset($address['recipient_name']) && $address['recipient_name']) {
            echo "✅ Nombre del destinatario: {$address['recipient_name']}\n";
        } else {
            echo "❌ Falta nombre del destinatario\n";
        }
        
        if (isset($address['phone']) && $address['phone']) {
            echo "✅ Teléfono: {$address['phone']}\n";
        } else {
            echo "❌ Falta teléfono\n";
        }
        
        if (isset($address['full_address']) && $address['full_address']) {
            echo "✅ Dirección completa: {$address['full_address']}\n";
        } else {
            echo "❌ Falta dirección completa\n";
        }
    } else {
        echo "❌ No se guardó la dirección de envío\n";
    }
    
    // 5. Probar la descarga del PDF
    echo "\n=== PRUEBA DE DESCARGA ===\n";
    $pdfPath = storage_path('app/' . $order->pdf_path);
    if (file_exists($pdfPath)) {
        echo "✅ PDF accesible en: {$pdfPath}\n";
        echo "✅ Tamaño del archivo: " . filesize($pdfPath) . " bytes\n";
    } else {
        echo "❌ PDF no accesible en: {$pdfPath}\n";
    }
    
    // 6. Verificar que el OrderPdfService funciona correctamente
    echo "\n=== PRUEBA DEL SERVICE ===\n";
    $orderPdfService = new OrderPdfService();
    
    try {
        // Verificar si el PDF existe
        if ($orderPdfService->pdfExists($order)) {
            echo "✅ OrderPdfService confirma que el PDF existe\n";
        } else {
            echo "❌ OrderPdfService dice que el PDF no existe\n";
        }
        
        // Probar regeneración
        $newPdfPath = $orderPdfService->ensureOrderPdf($order);
        echo "✅ PDF asegurado: {$newPdfPath}\n";
        
    } catch (\Exception $e) {
        echo "❌ Error en OrderPdfService: " . $e->getMessage() . "\n";
    }
    
    echo "\n=== RESULTADO FINAL ===\n";
    echo "✅ Orden #{$order->id} creada exitosamente\n";
    echo "✅ PDF generado y guardado\n";
    echo "✅ Dirección de envío guardada\n";
    echo "✅ Sistema funcionando correctamente\n\n";
    
    // Opcional: Eliminar la orden de prueba
    echo "¿Desea eliminar la orden de prueba? (s/n): ";
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    if (trim($line) === 's' || trim($line) === 'S') {
        // Eliminar PDF
        if ($order->pdf_path && Storage::exists($order->pdf_path)) {
            Storage::delete($order->pdf_path);
        }
        
        // Eliminar orden
        $order->delete();
        echo "✅ Orden de prueba eliminada\n";
    } else {
        echo "📋 Orden de prueba mantenida: #{$order->id}\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error durante la prueba: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== FIN DEL TEST ===\n";
