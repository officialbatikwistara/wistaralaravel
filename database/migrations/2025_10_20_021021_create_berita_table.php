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
        Schema::create('berita', function (Blueprint $table) {
            $table->id(); // kolom id
            $table->string('judul'); // judul berita
            $table->string('slug')->unique(); // slug unik untuk URL SEO
            $table->longText('konten'); // isi berita (panjang)
            $table->string('gambar')->nullable(); // link atau path gambar
            $table->date('tanggal'); // tanggal berita
            $table->string('sumber')->nullable(); // nama sumber berita
            $table->string('tautan_sumber')->nullable(); // tautan sumber berita
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita');
    }
};
