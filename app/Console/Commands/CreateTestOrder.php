<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\User;

class CreateTestOrder extends Command
{
    protected $signature = 'test:create-order';
    protected $description = 'Create a test order for PDF generation testing';

    public function handle()
    {
        try {
            $user = User::first();
            if (!$user) {
                $this->error("No hay usuarios en la base de datos");
                return;
            }

            $this->info("Creando orden de prueba para el usuario: {$user->email}");

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'TEST-' . time(),
                'status' => 1,
                'payment_method' => 3, // Efectivo
                'subtotal' => 25.50,
                'shipping_cost' => 5.00,
                'total' => 30.50,
                'content' => [
                    [
                        'id' => 1,
                        'name' => 'Producto de Prueba',
                        'price' => 25.50,
                        'quantity' => 1,
                        'subtotal' => 25.50,
                        'options' => []
                    ]
                ],
                'shipping_address' => [
                    'address' => 'Av. Principal 123',
                    'reference' => 'Frente al parque',
                    'province' => 'Pichincha',
                    'canton' => 'Quito',
                    'parish' => 'Centro',
                    'postal_code' => '170101',
                    'receiver_type' => 'me',
                    'receiver_name' => $user->name,
                    'receiver_full_name' => $user->name,
                    'receiver_phone' => '0987654321',
                    'receiver_email' => $user->email,
                    'receiver_document_type' => 'CI',
                    'receiver_document_number' => '1234567890'
                ]
            ]);

            $this->info("✅ Orden creada exitosamente!");
            $this->info("📝 ID: {$order->id}");
            $this->info("📄 Número: {$order->order_number}");
            $this->info("🗂️ PDF Path: " . ($order->pdf_path ?? 'NO GENERADO'));

            // Verificar si se generó el PDF
            if ($order->pdf_path) {
                $this->info("✅ PDF generado automáticamente por el Observer");
                if (\Storage::exists($order->pdf_path)) {
                    $size = \Storage::size($order->pdf_path);
                    $this->info("📊 Tamaño: " . number_format($size / 1024, 2) . " KB");
                } else {
                    $this->error("❌ PDF path guardado pero archivo no existe");
                }
            } else {
                $this->warn("⚠️ PDF no generado automáticamente");
                $this->info("Ejecuta: php artisan test:pdf-generation {$order->id}");
            }

        } catch (\Exception $e) {
            $this->error("Error creando orden: " . $e->getMessage());
            $this->error($e->getTraceAsString());
        }
    }
}
