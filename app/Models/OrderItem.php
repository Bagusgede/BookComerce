<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'book_id',
        'book_title',
        'price',
        'quantity',
        'subtotal',
        'format',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Get the order that owns the order item
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the book associated with the order item
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Get ebook delivery for this item (if it's an ebook)
     */
    public function ebookDelivery()
    {
        return $this->hasOne(EbookDelivery::class);
    }

    /**
     * Calculate subtotal automatically
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($orderItem) {
            $orderItem->subtotal = $orderItem->price * $orderItem->quantity;
        });
    }

    /**
     * Create order items from cart
     */
    public static function createFromCart($orderId, $cartItems)
    {
        $orderItems = [];

        foreach ($cartItems as $cartItem) {
            $orderItem = self::create([
                'order_id' => $orderId,
                'book_id' => $cartItem->book_id,
                'book_title' => $cartItem->book->title,
                'price' => $cartItem->book->final_price,
                'quantity' => $cartItem->quantity,
                'format' => $cartItem->book->format,
            ]);

            $orderItems[] = $orderItem;

            // Reduce book stock (only for physical books)
            if ($cartItem->book->format === 'physical' || $cartItem->book->format === 'both') {
                $cartItem->book->decrement('stock', $cartItem->quantity);
            }
        }

        return $orderItems;
    }
}
