<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Category extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];
    protected $casts = [
        'is_active' => 'boolean',
    ];

    //ambil semua buku dari kategori ini
    public function books()
    {
        return $this->hasMany(Book::class)->where('is_active', true);
    }
    //scope untuk kategori yang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    // ambil route key untuk model ini berdasarkan slug
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
