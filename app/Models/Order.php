<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'pdf_path',
        'content',
        'shipping_address',
        'payment_method',
        'payment_id',
        'delivery_type',
        'total',
        'status',
        'subtotal',
        'shipping_cost',
        'notes'
        ,
        'nota_cancelacion'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status' => 'integer',
        'content' => 'array',
        'shipping_address' => 'array',
    ];

    protected $hidden = [
        '_oldStatus',
        '_temp_old_status'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(uniqid());
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class);
    }

    public function hasShipment(): bool
    {
        return $this->shipment()->exists();
    }

    public function getPaymentMethodLabelAttribute()
    {
        return match ($this->payment_method) {
            0 => 'Transferencia',
            1 => 'Tarjeta',
            2 => 'Efectivo',
            default => 'Otro'
        };
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            1 => 'Pendiente',
            2 => 'Pago Verificado',
            3 => 'Preparando',
            4 => 'Asignado',
            5 => 'En Camino',
            6 => 'Entregado',
            7 => 'Cancelado',
            default => 'Pendiente'
        };
    }

    /**
     * Obtener el texto del estado para compatibilidad
     */
    public function getStatusTextAttribute()
    {
        return OrderStatus::from($this->status)->label();
    }

    /**
     * Obtener el enum del estado
     */
    public function getStatusEnumAttribute()
    {
        return OrderStatus::from($this->status);
    }
}
