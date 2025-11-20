<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Ubah kolom status_pembayaran
            $table->enum('status_pembayaran', [
                'belum_bayar',
                'menunggu_verifikasi',
                'lunas',
                'gagal'
            ])->default('belum_bayar')->change();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Kembalikan ke struktur lama jika rollback
            $table->enum('status_pembayaran', [
                'pending',
                'paid',
                'failed'
            ])->default('pending')->change();
        });
    }
};
