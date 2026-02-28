<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
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
        'order_id',
        'rating',
        'review',
        'is_approved',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
    ];

    /**
     * Get the user that wrote the review
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the book being reviewed
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Get the order associated with this review
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Approve the review
     */
    public function approve()
    {
        $this->is_approved = true;
        $this->save();
        return $this;
    }

    /**
     * Unapprove the review
     */
    public function unapprove()
    {
        $this->is_approved = false;
        $this->save();
        return $this;
    }

    /**
     * Scope to only include approved reviews
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope to only include pending reviews
     */
    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    /**
     * Get star rating display
     */
    public function getStarRatingAttribute()
    {
        return str_repeat('⭐', $this->rating);
    }

    /**
     * Check if user can review this book from this order
     */
    public static function canReview($userId, $bookId, $orderId)
    {
        // Check if order exists and belongs to user
        $order = Order::where('id', $orderId)
            ->where('user_id', $userId)
            ->where('status', 'delivered')
            ->first();

        if (!$order) {
            return false;
        }

        // Check if book is in the order
        $orderItem = OrderItem::where('order_id', $orderId)
            ->where('book_id', $bookId)
            ->first();

        if (!$orderItem) {
            return false;
        }

        // Check if already reviewed
        $existingReview = self::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->where('order_id', $orderId)
            ->first();

        return !$existingReview;
    }
}
