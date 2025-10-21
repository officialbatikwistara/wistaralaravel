<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    //
    use HasFactory;

    protected $table = 'order'; // Nama tabel
    protected $fillable = [
        'user_id',
        'nama',
        'telepon',
        'alamat',
        'catatan',
        'total',
        'status',
        'tipe_order',
        'ambil',
        'kirim',
    ];

    // Relasi ke order
    public function items()
    {
        return $this->hasMany(PesananItem::class, 'order_id');
    }

    // Relasi ke user (jika kamu pakai sistem user)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
