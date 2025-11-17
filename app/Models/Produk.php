<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'id_produk';
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

    // ============================
    //   RELASI CATEGORY
    // ============================
    public function kategori()
    {
        return $this->belongsTo(KategoriProduk::class, 'id_kategori', 'id_kategori');
    }

    // ============================
    //   RELASI REVIEWS
    // ============================
    public function reviews()
    {
        return $this->hasMany(Review::class, 'id_produk', 'id_produk');
    }

    // Review yang sudah disetujui
    public function approvedReviews()
    {
        return $this->reviews()->where('status', 'approved');
    }

    // ============================
    //   ACCESSOR: AVERAGE RATING
    // ============================
    public function getAverageRatingAttribute()
    {
        $avg = $this->approvedReviews()->avg('rating');
        return $avg ? round($avg, 1) : 0;
    }

    // ============================
    //   ACCESSOR: REVIEW COUNT
    // ============================
    public function getReviewCountAttribute()
    {
        return $this->approvedReviews()->count();
    }
}
