<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Jangan drop jika table sudah ada untuk menghindari masalah FK/produk data
        if (Schema::hasTable('orders')) {
            return;
        }

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('nama');
            $table->string('telepon');
            $table->text('alamat');
            $table->text('catatan')->nullable();
            $table->decimal('total', 15, 2);
            $table->enum('status', ['pending', 'proses', 'selesai', 'batal'])->default('pending');
            $table->enum('status_pembayaran', ['belum_bayar', 'menunggu_verifikasi', 'lunas', 'gagal'])->default('belum_bayar');
            $table->string('bukti_pembayaran')->nullable(); // Added missing bukti_pembayaran column
            $table->enum('tipe_order', ['ambil', 'kirim']);
            $table->string('metode_pembayaran');
            $table->date('tanggal_ambil')->nullable();
            $table->datetime('ambil')->nullable();
            $table->datetime('kirim')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
