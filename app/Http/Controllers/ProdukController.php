<?php

namespace App\Http\Controllers;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        // Ambil filter kategori dari URL (?kategori=xxx)
        $filter = $request->input('kategori', 'all');

        // Ambil semua kategori
        $kategori = DB::table('kategori_produk')->get();

        // Ambil semua produk (filter jika kategori dipilih)
        $produk = DB::table('produk')
            ->join('kategori_produk', 'produk.id_kategori', '=', 'kategori_produk.id_kategori')
            ->select('produk.*', 'kategori_produk.nama_kategori')
            ->when($filter !== 'all', function ($query) use ($filter) {
                $query->where('kategori_produk.id_kategori', $filter);
            })
            ->orderBy('tanggal_upload', 'desc')
            ->get();

        return view('katalog', compact('kategori', 'produk', 'filter'));
    }
    public function nonaktif($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->status = 'nonaktif';   // ✅ pakai string
        $produk->save();

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dinonaktifkan.');
    }

    public function aktifkan($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->status = 'aktif';   // ✅ pakai string
        $produk->save();

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diaktifkan kembali.');
    }

}

/* 📌 Penjelasan:
$filter → ambil nilai kategori dari URL (?kategori=...).
DB::table('produk') → mengambil data produk dari tabel DB.
join('kategori_produk', ...) → menggabungkan tabel produk dan kategori.
when(...) → jika kategori tidak “all”, maka query akan difilter.
->get() → ambil semua data.
compact('kategori', 'produk', 'filter') → kirim ke Blade. */