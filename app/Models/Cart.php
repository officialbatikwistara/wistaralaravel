<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';
    protected $fillable = ['user_id', 'id_produk', 'qty'];

    public function produk()
    {
        // foreign key = id_produk, primary key = id_produk
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}

