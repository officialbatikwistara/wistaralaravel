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
        'tanggal_ambil'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_total' => 'decimal:2'
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

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    // Accessor to get formatted order code
    public function getOrderCodeAttribute()
    {
        return "WST-" . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }
}
