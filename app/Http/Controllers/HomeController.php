<?php

namespace App\Http\Controllers;

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

        // Ambil 8 produk terbaru
        $produk = DB::table('produk')
            ->join('kategori_produk', 'produk.id_kategori', '=', 'kategori_produk.id_kategori')
            ->select('produk.*', 'kategori_produk.nama_kategori')
            ->orderBy('tanggal_upload', 'desc')
            ->limit(8)
            ->get();

        return view('home', compact('berita', 'produk'));
    }
}
