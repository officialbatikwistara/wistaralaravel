<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'id_produk';

    protected $fillable = [
        'nama_produk',
        'slug',
        'kategori_id',
        'harga',
        'stok',
        'deskripsi',
        'gambar',
        'status'
    ];

    /**
     * Relationship to Kategori
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id');
    }

    /**
     * Relationship to Reviews
     * Update foreign key to match your database column name
     */
    public function reviews()
    {
        // Try common column names: id_produk, product_id, or produk_id
        return $this->hasMany(Review::class, 'id_produk', 'id_produk');
    }

    /**
     * Get average rating - dengan error handling
     */
    public function getAverageRatingAttribute()
    {
        try {
            return $this->reviews()->avg('rating') ?? 0;
        } catch (\Exception $e) {
            \Log::warning('Failed to get average rating', [
                'produk_id' => $this->id_produk,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }

    /**
     * Get review count - dengan error handling
     */
    public function getReviewCountAttribute()
    {
        try {
            return $this->reviews()->count();
        } catch (\Exception $e) {
            return 0;
        }
    }
}
