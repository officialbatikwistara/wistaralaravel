<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $table = 'orders';

    protected $primaryKey = 'id';
    public $incrementing = false; // ID berupa string
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'coupon_id',
        'nama',
        'telepon',
        'alamat',
        'catatan',
        'total',
        'discount_amount',
        'final_total',
        'status',
        'status_pembayaran',
        'bukti_pembayaran',
        'tipe_order',
        'metode_pembayaran',
        'tanggal_ambil',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->id)) {
                // Format ID: WST-YYYYMMDD-ABCD
                $order->id = 'WST-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
            }
        });
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    /**
     * 🔥 ACCESSOR UNTUK MENAMPILKAN order_code DI BLADE
     */
    public function getOrderCodeAttribute()
    {
        return $this->id;
    }
}
