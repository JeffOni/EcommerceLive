<?php

namespace App\Observers;

use App\Models\Order;
use App\Notifications\OrderStatusChanged;
use App\Services\OrderPdfService;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    protected $orderPdfService;

    public function __construct(OrderPdfService $orderPdfService)
    {
        $this->orderPdfService = $orderPdfService;
    }

    /**
     * Handle the Order "updating" event.
     */
    public function updating(Order $order)
    {
        // Guardar el estado anterior para comparación sin agregarlo a los atributos del modelo
        if ($order->isDirty('status')) {
            $this->oldStatus = $order->getOriginal('status');
        }
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order)
    {
        // Verificar si el estado cambió usando la variable de clase
        if (
            isset($this->oldStatus) &&
            $this->oldStatus !== $order->status &&
            $order->user
        ) {

            // Enviar notificación al cliente
            $order->user->notify(new OrderStatusChanged(
                $order,
                $this->oldStatus,
                $order->status
            ));

            // Limpiar la variable
            unset($this->oldStatus);
        }

        // Si el estado cambió a "Pago Verificado" (2) y no tiene PDF, generarlo
        if ($order->status == 2 && (!$order->pdf_path || !$this->orderPdfService->pdfExists($order))) {
            try {
                $this->orderPdfService->generateOrderPdf($order);
                Log::info("PDF regenerado para la orden verificada {$order->id}");
            } catch (\Exception $e) {
                Log::error("Error regenerando PDF para la orden {$order->id}: " . $e->getMessage());
            }
        }
    }

    private $oldStatus;

    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order)
    {
        // Notificar al cliente sobre la creación del pedido
        if ($order->user) {
            $order->user->notify(new OrderStatusChanged(
                $order,
                null,
                $order->status
            ));
        }

        // Generar PDF automáticamente al crear la orden
        try {
            $this->orderPdfService->generateOrderPdf($order);
            Log::info("PDF generado automáticamente para la orden {$order->id}");
        } catch (\Exception $e) {
            Log::error("Error generando PDF para la orden {$order->id}: " . $e->getMessage());
        }
    }
}
