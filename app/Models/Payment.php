<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'payment_method',
        'amount',
        'status',
        'transaction_id',
        'transaction_number',
        'receipt_path',
        'comments',
        'cart_data',
        'response_data',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'cart_data' => 'array',
        'response_data' => 'array',
        'amount' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    /**
     * Relación con el usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con la orden
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relación con el usuario que verificó el pago
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Scopes para filtrar por método de pago
     */
    public function scopePayPhone($query)
    {
        return $query->where('payment_method', 'payphone');
    }

    public function scopeBankTransfer($query)
    {
        return $query->where('payment_method', 'bank_transfer');
    }

    public function scopeCashOnDelivery($query)
    {
        return $query->where('payment_method', 'cash_on_delivery');
    }

    public function scopeQrDeUna($query)
    {
        return $query->where('payment_method', 'qr_deuna');
    }

    /**
     * Scopes para filtrar por estado
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePendingVerification($query)
    {
        return $query->where('status', 'pending_verification');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
