<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Services\OrderPdfService;

class TestPdfGeneration extends Command
{
    protected $signature = 'test:pdf-generation {order_id?}';
    protected $description = 'Test PDF generation for orders';

    public function handle()
    {
        $orderId = $this->argument('order_id');

        if ($orderId) {
            $order = Order::find($orderId);
            if (!$order) {
                $this->error("Orden con ID {$orderId} no encontrada");
                return;
            }
        } else {
            // Buscar la última orden
            $order = Order::latest()->first();
            if (!$order) {
                $this->error("No hay órdenes en la base de datos");
                return;
            }
        }

        $this->info("Probando generación de PDF para la orden {$order->id}...");

        try {
            $pdfService = app(OrderPdfService::class);
            $pdfPath = $pdfService->generateOrderPdf($order);

            $this->info("✅ PDF generado exitosamente!");
            $this->info("📁 Ruta: {$pdfPath}");
            $this->info("🔗 PDF Path en DB: " . ($order->fresh()->pdf_path ?? 'NO GUARDADO'));

            // Verificar que el archivo existe
            if (\Storage::exists($pdfPath)) {
                $size = \Storage::size($pdfPath);
                $this->info("📊 Tamaño del archivo: " . number_format($size / 1024, 2) . " KB");
            } else {
                $this->error("❌ El archivo PDF no existe en storage");
            }

        } catch (\Exception $e) {
            $this->error("❌ Error generando PDF: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
        }
    }
}
