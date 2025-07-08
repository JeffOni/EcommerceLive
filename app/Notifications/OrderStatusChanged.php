<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;
    protected $oldStatus;
    protected $newStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, $oldStatus, $newStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
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
        $statusMessages = [
            'pending' => 'Tu pedido está siendo procesado',
            'processing' => 'Tu pedido está en preparación',
            'shipped' => 'Tu pedido ha sido enviado',
            'delivered' => 'Tu pedido ha sido entregado',
            'cancelled' => 'Tu pedido ha sido cancelado'
        ];

        $message = new MailMessage();
        $message->subject('Actualización de tu pedido #' . $this->order->id)
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Te informamos sobre una actualización en tu pedido.')
            ->line('**Número de pedido:** #' . $this->order->id)
            ->line('**Estado anterior:** ' . ucfirst($this->oldStatus))
            ->line('**Estado actual:** ' . ucfirst($this->newStatus))
            ->line($statusMessages[$this->newStatus] ?? 'Tu pedido ha sido actualizado.');

        if ($this->newStatus === 'shipped' && $this->order->shipment) {
            $message->line('**Código de seguimiento:** ' . $this->order->shipment->tracking_number);

            if ($this->order->shipment->deliveryDriver) {
                $message->line('**Repartidor:** ' . $this->order->shipment->deliveryDriver->name)
                    ->line('**Teléfono:** ' . $this->order->shipment->deliveryDriver->phone);
            }
        }

        $message->action('Ver Detalles del Pedido', route('orders.tracking.show', $this->order->id))
            ->line('Gracias por confiar en nosotros.');

        return $message;
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'message' => 'Tu pedido #' . $this->order->id . ' cambió de estado: ' . $this->oldStatus . ' → ' . $this->newStatus,
            'tracking_url' => route('orders.tracking.show', $this->order->id)
        ];
    }
}
