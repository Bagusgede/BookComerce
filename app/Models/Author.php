<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'bio',
        'photo',
        'phone',
        'social_media',
        'is_active',
    ];

    protected $casts = [
        'social_media' => 'json',
        'is_active' => 'boolean',
    ];

    /**
     * Get all books by this author
     */
    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_author')
            ->withPivot('order')
            ->orderBy('book_author.order');
    }

    /**
     * Get all blog posts by this author
     */
    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class)->where('is_published', true);
    }

    /**
     * Get all published blog posts
     */
    public function publishedBlogPosts()
    {
        return $this->blogPosts()->where('is_published', true);
    }

    /**
     * Scope untuk hanya authors yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get route key berdasarkan slug
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
