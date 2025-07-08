<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentVerified extends Notification implements ShouldQueue
{
    use Queueable;

    protected $payment;
    protected $status;

    /**
     * Create a new notification instance.
     */
    public function __construct(Payment $payment, $status)
    {
        $this->payment = $payment;
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $message = new MailMessage();

        if ($this->status === 'approved') {
            $message->subject('¡Pago aprobado! - Pedido #' . $this->payment->order_id)
                ->greeting('¡Excelentes noticias, ' . $notifiable->name . '!')
                ->line('Tu pago ha sido verificado y aprobado exitosamente.')
                ->line('**Número de pedido:** #' . $this->payment->order_id)
                ->line('**Monto:** $' . number_format($this->payment->amount, 2))
                ->line('**Método de pago:** ' . ucfirst($this->payment->payment_method))
                ->line('Tu pedido ya está siendo procesado y pronto recibirás más actualizaciones.')
                ->action('Ver Estado del Pedido', route('orders.tracking.show', $this->payment->order_id))
                ->line('¡Gracias por tu compra!');
        } else {
            $message->subject('Pago rechazado - Pedido #' . $this->payment->order_id)
                ->greeting('Hola ' . $notifiable->name . ',')
                ->line('Lamentamos informarte que tu pago no pudo ser verificado.')
                ->line('**Número de pedido:** #' . $this->payment->order_id)
                ->line('**Monto:** $' . number_format($this->payment->amount, 2))
                ->line('**Método de pago:** ' . ucfirst($this->payment->payment_method));

            if ($this->payment->rejection_reason) {
                $message->line('**Motivo:** ' . $this->payment->rejection_reason);
            }

            $message->line('Por favor, verifica los datos de pago y vuelve a intentarlo.')
                ->action('Revisar Pedido', route('orders.tracking.show', $this->payment->order_id))
                ->line('Si tienes dudas, no dudes en contactarnos.');
        }

        return $message;
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        $message = $this->status === 'approved'
            ? 'Tu pago para el pedido #' . $this->payment->order_id . ' ha sido aprobado'
            : 'Tu pago para el pedido #' . $this->payment->order_id . ' ha sido rechazado';

        return [
            'payment_id' => $this->payment->id,
            'order_id' => $this->payment->order_id,
            'status' => $this->status,
            'amount' => $this->payment->amount,
            'message' => $message,
            'tracking_url' => route('orders.tracking.show', $this->payment->order_id)
        ];
    }
}
