<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil 4 berita terbaru
        $berita = DB::table('berita')
            ->orderBy('tanggal', 'desc')
            ->limit(4)
            ->get();

        // Ambil 8 produk terbaru + kategori + reviews approved
        $produk = Produk::with([
                'kategori:id_kategori,nama_kategori',
                'approvedReviews:id,id_produk,rating,status'
            ])
            ->orderBy('tanggal_upload', 'desc')
            ->limit(8)
            ->get();

        return view('home', compact('berita', 'produk'));
    }
}
