<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'book_id',
        'quantity',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'integer',
    ];

    /**
     * Get the user that owns the cart
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the book in the cart
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Get subtotal for this cart item
     */
    public function getSubtotalAttribute()
    {
        return $this->book->final_price * $this->quantity;
    }

    /**
     * Get all cart items for a user with book details
     */
    public static function getCartItems($userId)
    {
        return self::where('user_id', $userId)
            ->with('book.category')
            ->get();
    }

    /**
     * Get total price of all items in cart
     */
    public static function getCartTotal($userId)
    {
        $items = self::getCartItems($userId);
        return $items->sum('subtotal');
    }

    /**
     * Get total items count in cart
     */
    public static function getCartCount($userId)
    {
        return self::where('user_id', $userId)->sum('quantity');
    }

    /**
     * Add or update item in cart
     */
    public static function addToCart($userId, $bookId, $quantity = 1)
    {
        $cart = self::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->first();

        if ($cart) {
            $cart->quantity += $quantity;
            $cart->save();
        } else {
            $cart = self::create([
                'user_id' => $userId,
                'book_id' => $bookId,
                'quantity' => $quantity,
            ]);
        }

        return $cart;
    }

    /**
     * Update quantity
     */
    public function updateQuantity($quantity)
    {
        $this->quantity = $quantity;
        $this->save();
        return $this;
    }

    /**
     * Clear cart for user
     */
    public static function clearCart($userId)
    {
        return self::where('user_id', $userId)->delete();
    }
}
