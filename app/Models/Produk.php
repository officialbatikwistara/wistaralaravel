<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'id_produk';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'nama_produk',
        'slug',
        'deskripsi',
        'harga',
        'stok',
        'gambar',
        'id_kategori',
        'link_shopee',
        'link_tiktok',
        'status',
        'tanggal_upload',
        'tanggal_update'
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriProduk::class, 'id_kategori', 'id_kategori');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'id_produk', 'id_produk');
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class, 'id_produk', 'id_produk')
                    ->where('status', 'approved');
    }

    // ⭐ FIX: gunakan field dari withAvg / withCount
    public function getAverageRatingAttribute()
    {
        return round($this->attributes['average_rating'] ?? 0, 1);
    }

    public function getReviewCountAttribute()
    {
        return $this->attributes['review_count'] ?? 0;
    }
}
