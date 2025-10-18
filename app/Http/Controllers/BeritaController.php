<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BeritaController extends Controller
{
    public function home()
    {
        // Ambil 4 berita terbaru
        $berita = DB::table('berita')
            ->orderBy('tanggal', 'desc')
            ->limit(4)
            ->get();

        // Ambil 4 produk terbaru
        // $produk = DB::table('produk')
        //     ->join('kategori_produk', 'produk.id_kategori', '=', 'kategori_produk.id_kategori')
        //     ->select('produk.*', 'kategori_produk.nama_kategori')
        //     ->orderBy('tanggal_upload', 'desc')
        //     ->limit(4)
        //     ->get();

        return view('home', compact('berita', 'produk'));
    }

public function index(Request $request)
{
    $search = $request->input('search');
    $limit = $request->input('limit', 8);

    $berita = DB::table('berita')
        ->when($search, function ($query, $search) {
            $query->where('judul', 'like', "%{$search}%")
                  ->orWhere('konten', 'like', "%{$search}%");
        })
        ->orderBy('tanggal', 'desc')
        ->paginate($limit)
        ->appends(['search' => $search, 'limit' => $limit]);

    return view('berita', compact('berita', 'search', 'limit'));
}


    public function show($slug)
    {
        $berita = DB::table('berita')->where('slug', $slug)->first();

        if (!$berita) {
            abort(404);
        }

        return view('berita-detail', compact('berita'));
    }
}
