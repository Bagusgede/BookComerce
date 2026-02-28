<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EbookDelivery extends Model
{
    use HasFactory;

    protected $table = 'ebook_deliveries';

    protected $fillable = [
        'order_item_id',
        'email',
        'download_token',
        'sent_at',
        'expired_at',
        'download_count',
        'last_downloaded_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'expired_at' => 'datetime',
        'last_downloaded_at' => 'datetime',
        'download_count' => 'integer',
    ];

    /**
     * Get the order item associated with this delivery
     */
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    /**
     * Check if the download link has expired
     */
    public function isExpired()
    {
        return now()->isAfter($this->expired_at);
    }

    /**
     * Check if link is still valid
     */
    public function isValid()
    {
        return !$this->isExpired();
    }

    /**
     * Increment download count
     */
    public function recordDownload()
    {
        $this->increment('download_count');
        $this->update(['last_downloaded_at' => now()]);
    }

    /**
     * Scope untuk find by token
     */
    public function scopeByToken($query, $token)
    {
        return $query->where('download_token', $token);
    }

    /**
     * Scope untuk find by email
     */
    public function scopeByEmail($query, $email)
    {
        return $query->where('email', $email);
    }

    /**
     * Scope untuk active deliveries only
     */
    public function scopeActive($query)
    {
        return $query->where('expired_at', '>', now());
    }
}
