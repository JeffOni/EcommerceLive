<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\User;
use App\Models\Address;
use App\Services\OrderPdfService;

class TestOrderFlow extends Command
{
    protected $signature = 'test:order-flow';
    protected $description = 'Test the order flow with address and PDF generation';

    public function handle()
    {
        $this->info('Testing order flow...');

        // Buscar un usuario de prueba
        $user = User::first();
        if (!$user) {
            $this->error('No hay usuarios en la base de datos');
            return;
        }

        // Crear una dirección de prueba
        $address = [
            'address' => 'Calle Principal 123',
            'reference' => 'Casa azul al lado del parque',
            'province' => 'Pichincha',
            'canton' => 'Quito',
            'parish' => 'La Mariscal',
            'postal_code' => '170515',
            'phone' => '0987654321',
            'recipient_name' => 'Juan Pérez',
            'recipient_document' => '1234567890',
            'type' => 'home',
            'full_address' => 'Calle Principal 123, La Mariscal, Quito, Pichincha'
        ];

        // Crear una orden de prueba
        $order = Order::create([
            'user_id' => $user->id,
            'content' => [
                [
                    'id' => 1,
                    'name' => 'Producto de Prueba',
                    'price' => 10.00,
                    'quantity' => 2,
                    'subtotal' => 20.00
                ]
            ],
            'shipping_address' => $address,
            'payment_method' => 2,
            'total' => 25.00,
            'subtotal' => 20.00,
            'shipping_cost' => 5.00,
            'status' => 2,
            'notes' => 'Orden de prueba para testing'
        ]);

        $this->info("Orden creada con ID: {$order->id}");
        $this->info("Shipping Address: " . json_encode($order->shipping_address, JSON_PRETTY_PRINT));

        // Intentar generar PDF
        try {
            $pdfService = new OrderPdfService();
            $pdfPath = $pdfService->generateOrderPdf($order);
            $this->info("PDF generado exitosamente: {$pdfPath}");

            // Verificar que el archivo existe
            if (file_exists(storage_path('app/' . $pdfPath))) {
                $this->info("PDF confirmado en sistema de archivos");
            } else {
                $this->error("PDF no encontrado en sistema de archivos");
            }
        } catch (\Exception $e) {
            $this->error("Error generando PDF: " . $e->getMessage());
        }

        $this->info('Test completado');
    }
}
