<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payment';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'payment_method',
        'payment_channel',
        'status',
        'amount',
        'transaction_id',
        'external_id',
        'payment_url',
        'payment_response',
        'payment_proof',
        'paid_at',
        'expired_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'payment_response' => 'array',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    /**
     * Get the order that owns the payment
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if payment is successful
     */
    public function isSuccess(): bool
    {
        return $this->status === 'success';
    }

    /**
     * Check if payment is failed
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Check if payment is expired
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired' ||
            ($this->expired_at && now()->isAfter($this->expired_at));
    }

    /**
     * Mark payment as success
     */
    public function markAsSuccess()
    {
        $this->status = 'success';
        $this->paid_at = now();
        $this->save();

        // Update order status
        $this->order->updateStatus('paid');

        return $this;
    }

    /**
     * Mark payment as failed
     */
    public function markAsFailed()
    {
        $this->status = 'failed';
        $this->save();

        return $this;
    }

    /**
     * Mark payment as expired
     */
    public function markAsExpired()
    {
        $this->status = 'expired';
        $this->save();

        // Optionally cancel the order
        $this->order->updateStatus('cancelled');

        return $this;
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get successful payments
     */
    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Get status badge color for display
     */
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'success' => 'green',
            'failed' => 'red',
            'expired' => 'gray',
            default => 'gray'
        };
    }

    /**
     * Get status label in Indonesian
     */
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending' => 'Menunggu Pembayaran',
            'success' => 'Berhasil',
            'failed' => 'Gagal',
            'expired' => 'Kadaluarsa',
            default => 'Unknown'
        };
    }

    /**
     * Get payment method label
     */
    public function getPaymentMethodLabelAttribute()
    {
        return match ($this->payment_method) {
            'midtrans' => 'Midtrans',
            'xendit' => 'Xendit',
            'manual_transfer' => 'Transfer Bank Manual',
            'cod' => 'Cash on Delivery',
            default => ucfirst($this->payment_method)
        };
    }
}
