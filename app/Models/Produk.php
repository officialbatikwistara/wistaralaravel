<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'id_produk';
    public $timestamps = false;

    protected $fillable = [
        'nama_produk', 'deskripsi', 'gambar', 'harga', 'tanggal_upload',
        'id_kategori', 'link_shopee', 'link_tiktok'
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriProduk::class, 'id_kategori');
    }

    public function carts()
    {
    return $this->hasMany(Cart::class, 'produk_id', 'id_produk');
    }

}
