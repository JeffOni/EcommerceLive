<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_method',
        'amount',
        'status',
        'transaction_id',
        'transaction_number',
        'receipt_path',
        'comments',
        'cart_data',
        'response_data',
    ];

    protected $casts = [
        'cart_data' => 'array',
        'response_data' => 'array',
        'amount' => 'decimal:2',
    ];

    /**
     * Relación con el usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
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
