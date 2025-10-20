<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id('id_produk'); // primary key
            $table->string('nama_produk'); // nama produk
            $table->string('slug')->unique(); // slug unik untuk URL
            $table->longText('deskripsi')->nullable(); // deskripsi produk
            $table->decimal('harga', 15, 2); // harga produk
            $table->integer('stok')->default(0); // stok produk
            $table->string('gambar')->nullable(); // path gambar
            $table->unsignedBigInteger('id_kategori'); // relasi ke kategori
            $table->string('link_shopee')->nullable(); // tautan Shopee
            $table->string('link_tiktok')->nullable(); // tautan TikTok Shop
            $table->timestamp('tanggal_upload')->useCurrent(); // tanggal upload
            $table->timestamp('tanggal_update')->nullable()->useCurrentOnUpdate(); // tanggal update
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif'); // status produk
            $table->timestamps(); // created_at & updated_at

            // Foreign key ke tabel kategori_produk
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori_produk')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
