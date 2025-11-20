<?php

namespace App\Http\Controllers;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('kategori', 'all');

        // Ambil semua kategori
        $kategori = DB::table('kategori_produk')->get();

        // Ambil produk dengan relasi kategori + rating
        $produk = Produk::with('kategori:id_kategori,nama_kategori')
            ->withAvg('approvedReviews as average_rating', 'rating')
            ->withCount('approvedReviews as review_count')
            ->when($filter !== 'all', function ($query) use ($filter) {
                $query->where('id_kategori', $filter);
            })
            ->orderBy('tanggal_upload', 'desc')
            ->get()
            ->map(function ($p) {
                $p->average_rating = round($p->average_rating ?? 0, 1);
                return $p;
            });

        return view('katalog', compact('kategori', 'produk', 'filter'));
    }

    public function show($slug)
    {
        $product = Produk::with('kategori')
            ->withCount(['approvedReviews as reviews_count'])
            ->withAvg(['approvedReviews as reviews_avg_rating' => function ($q) {
                $q->where('status', 'approved');
            }], 'rating')
            ->where('slug', $slug)
            ->firstOrFail();

        return view('produk.show', compact('product'));
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
