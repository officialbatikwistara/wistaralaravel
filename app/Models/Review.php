<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = "reviews";

    protected $fillable = [
        'user_id',
        'id_produk',
        'order_id',
        'rating',
        'comment',
        'photos',
        'video',
        'status',
        'is_verified_purchase',
        'helpful_count'
    ];

    // 🔗 Relasi ke User
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    // 🔗 Relasi ke Produk
    public function produk()
    {
        return $this->belongsTo(\App\Models\Produk::class, 'id_produk', 'id_produk');
    }

    // 🔗 Relasi ke Order
    public function order()
    {
        return $this->belongsTo(\App\Models\Order::class, 'order_id', 'id');
    }
}
