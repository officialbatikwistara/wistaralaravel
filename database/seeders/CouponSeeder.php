<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Coupon::create([
            'code' => 'WELCOME10',
            'name' => 'Diskon Selamat Datang',
            'description' => 'Diskon 10% untuk pembelian pertama',
            'type' => 'percentage',
            'value' => 10,
            'min_order_amount' => 50000,
            'max_discount' => 25000,
            'usage_limit' => 100,
            'starts_at' => now(),
            'expires_at' => now()->addDays(30),
            'is_active' => true
        ]);

        \App\Models\Coupon::create([
            'code' => 'BATIK20',
            'name' => 'Diskon Batik',
            'description' => 'Diskon Rp 20.000 untuk semua produk batik',
            'type' => 'fixed',
            'value' => 20000,
            'min_order_amount' => 100000,
            'usage_limit' => 50,
            'starts_at' => now(),
            'expires_at' => now()->addDays(14),
            'is_active' => true
        ]);

        \App\Models\Coupon::create([
            'code' => 'FLASH50',
            'name' => 'Flash Sale',
            'description' => 'Diskon 50% untuk produk tertentu',
            'type' => 'percentage',
            'value' => 50,
            'min_order_amount' => 200000,
            'max_discount' => 100000,
            'usage_limit' => 10,
            'starts_at' => now(),
            'expires_at' => now()->addHours(24),
            'is_active' => true
        ]);
    }
}
