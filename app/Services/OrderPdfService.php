<?php

namespace App\Services;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrderPdfService
{
    /**
     * Generar PDF para una orden
     */
    public function generateOrderPdf(Order $order): string
    {
        try {
            // Cargar datos necesarios para el PDF
            $order->load(['user', 'payment']);

            // Generar el PDF usando la vista existente
            $pdf = Pdf::loadView('orders.invoice', compact('order'));

            // Configurar el PDF
            $pdf->setPaper('A4', 'portrait');

            // Generar nombre único para el archivo
            $fileName = 'orders/order-' . $order->id . '-' . Str::random(8) . '.pdf';

            // Generar el contenido del PDF
            $pdfContent = $pdf->output();

            // Asegurar que el directorio existe
            $directory = dirname(storage_path('app/' . $fileName));
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            // Guardar el PDF en el storage
            $saved = Storage::put($fileName, $pdfContent);

            if ($saved) {
                // Verificar que el archivo se guardó correctamente
                if (Storage::exists($fileName)) {
                    \Log::info("PDF guardado exitosamente: {$fileName}");

                    // Verificar tamaño del archivo
                    $fileSize = Storage::size($fileName);
                    \Log::info("Tamaño del archivo PDF: {$fileSize} bytes");

                    // Actualizar la orden con la ruta del PDF
                    $order->update(['pdf_path' => $fileName]);

                    return $fileName;
                } else {
                    \Log::error("Error: PDF no se pudo guardar en: {$fileName}");
                    throw new \Exception("No se pudo guardar el PDF en el storage");
                }
            } else {
                \Log::error("Error: Storage::put falló para: {$fileName}");
                throw new \Exception("Falló al escribir el archivo PDF");
            }

        } catch (\Exception $e) {
            \Log::error("Error generando PDF para orden {$order->id}: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());
            throw $e;
        }
    }

    /**
     * Regenerar PDF si no existe o está corrupto
     */
    public function ensureOrderPdf(Order $order): string
    {
        // Verificar si el PDF existe y es válido
        if ($order->pdf_path && Storage::exists($order->pdf_path)) {
            return $order->pdf_path;
        }

        // Si no existe o no es válido, generar uno nuevo
        return $this->generateOrderPdf($order);
    }

    /**
     * Obtener la ruta completa del PDF
     */
    public function getPdfPath(Order $order): ?string
    {
        if (!$order->pdf_path) {
            return null;
        }

        return storage_path('app/' . $order->pdf_path);
    }

    /**
     * Verificar si el PDF existe
     */
    public function pdfExists(Order $order): bool
    {
        return $order->pdf_path && Storage::exists($order->pdf_path);
    }
}
