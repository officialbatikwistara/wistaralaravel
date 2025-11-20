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

        // Ambil produk + kategori + avg rating + jumlah review
$produk = Produk::with([
        'kategori:id_kategori,nama_kategori',
        'approvedReviews:id,id_produk,rating,status'
    ])
    ->withAvg('approvedReviews as average_rating', 'rating')
    ->withCount('approvedReviews as review_count')
    ->orderBy('tanggal_upload', 'desc')
    ->limit(8)
    ->get()
    ->map(function ($p) {
        $p->average_rating = $p->average_rating ? round($p->average_rating, 1) : 0;
        return $p;
    });


        return view('home', compact('berita', 'produk'));
    }
}
