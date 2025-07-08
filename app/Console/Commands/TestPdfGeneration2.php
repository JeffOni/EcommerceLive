<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Services\OrderPdfService;

class TestPdfGeneration2 extends Command
{
    protected $signature = 'test:pdf-generate2';
    protected $description = 'Probar la generación de PDF para la última orden';

    public function handle()
    {
        $order = Order::latest()->first();

        if (!$order) {
            $this->error('No se encontraron órdenes en la base de datos.');
            return;
        }

        $this->info("Intentando generar PDF para orden: {$order->id}");

        try {
            $service = app(OrderPdfService::class);
            $path = $service->generateOrderPdf($order);
            $this->info("PDF generado exitosamente: {$path}");

            // Verificar que se guardó en la base de datos
            $order->refresh();
            if ($order->pdf_path) {
                $this->info("PDF_path en orden: {$order->pdf_path}");
            } else {
                $this->warn("El campo pdf_path no se actualizó en la orden.");
            }

        } catch (\Exception $e) {
            $this->error("Error al generar PDF: " . $e->getMessage());
            $this->line("Stack trace:");
            $this->line($e->getTraceAsString());
        }
    }
}
