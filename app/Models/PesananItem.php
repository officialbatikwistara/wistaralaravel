<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PesananItem extends Model
{
    //
    use HasFactory;

    protected $table = 'order_items'; // Nama tabel di database

    protected $fillable = [
        'order_id',
        'id_produk',
        'qty',
        'harga',
        'subtotal',
    ];

    // Relasi: item ini milik satu order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Relasi ke produk
    public function product()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}
