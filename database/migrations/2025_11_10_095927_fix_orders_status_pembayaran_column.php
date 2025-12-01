<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $statusValues = ['belum_bayar', 'menunggu_verifikasi', 'lunas', 'gagal'];

            if (!Schema::hasColumn('orders', 'status_pembayaran')) {
                // Tambah kolom jika belum ada
                $table->enum('status_pembayaran', $statusValues)->default('belum_bayar');
            } else {
                // Ubah definisi kolom jika sudah ada
                $table->enum('status_pembayaran', $statusValues)->default('belum_bayar')->change();
            }
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
