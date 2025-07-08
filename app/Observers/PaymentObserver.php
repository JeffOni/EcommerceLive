<?php

namespace App\Observers;

use App\Models\Payment;
use App\Notifications\PaymentVerified;

class PaymentObserver
{
    /**
     * Almacenar el estado anterior usando el ID del payment como key
     */
    private static $oldStatuses = [];

    /**
     * Handle the Payment "updating" event.
     */
    public function updating(Payment $payment)
    {
        // Guardar el estado anterior usando el ID del payment como identificador
        if ($payment->isDirty('status')) {
            self::$oldStatuses[$payment->id] = $payment->getOriginal('status');
        }
    }

    /**
     * Handle the Payment "updated" event.
     */
    public function updated(Payment $payment)
    {
        // Verificar si el estado cambió y fue verificado (aprobado o rechazado)
        $oldStatus = self::$oldStatuses[$payment->id] ?? null;

        if (
            $oldStatus &&
            $oldStatus !== $payment->status &&
            in_array($payment->status, ['approved', 'rejected']) &&
            $payment->user
        ) {
            // Enviar notificación al cliente
            $payment->user->notify(new PaymentVerified(
                $payment,
                $payment->status
            ));

            // Limpiar el estado guardado
            unset(self::$oldStatuses[$payment->id]);
        }
    }
}
