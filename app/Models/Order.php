<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'nama',
        'telepon',
        'alamat',
        'catatan',
        'total',
        'status',
        'status_pembayaran',
        'bukti_pembayaran',
        'tipe_order',
        'metode_pembayaran',
        'tanggal_ambil'
    ];

    protected $appends = ['order_code'];

    public static function boot()
    {
        parent::boot();

        // Remove the creating event that was setting string IDs
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    // Accessor to get formatted order code
    public function getOrderCodeAttribute()
    {
        return "WST-" . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }
}
