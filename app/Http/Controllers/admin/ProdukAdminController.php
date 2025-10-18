<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProdukAdminController extends Controller
{
    public function index()
    {
        $produk = DB::table('produk')
            ->join('kategori_produk', 'produk.id_kategori', '=', 'kategori_produk.id_kategori')
            ->select('produk.*', 'kategori_produk.nama_kategori')
            ->orderBy('tanggal_upload', 'desc')
            ->get();

        return view('admin.produk.index', compact('produk'));
    }

    public function create()
    {
        $kategori = DB::table('kategori_produk')->get();
        return view('admin.produk.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'id_kategori' => 'required|integer'
        ]);

        // Upload gambar jika ada
        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/produk'), $filename);
            $gambarPath = 'uploads/produk/'.$filename;
        }

        \DB::table('produk')->insert([
            'nama_produk' => $request->nama_produk,
            'slug' => \Str::slug($request->nama_produk),
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'id_kategori' => $request->id_kategori,
            'gambar' => $gambarPath,
            'link_shopee' => $request->link_shopee,
            'link_tiktok' => $request->link_tiktok,
            'tanggal_upload' => now(),
        ]);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit($id_produk)
    {
        $produk = DB::table('produk')->where('id_produk', $id_produk)->first();
        $kategori = DB::table('kategori_produk')->get();
        return view('admin.produk.edit', compact('produk', 'kategori'));
    }

    public function update(Request $request, $id_produk)
    {
        $produk = DB::table('produk')->where('id_produk', $id_produk)->first();
        $gambarPath = $produk->gambar;

        // Jika ada gambar baru
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/produk'), $filename);
            $gambarPath = 'uploads/produk/'.$filename;

            // Hapus gambar lama jika ada
            if ($produk->gambar && file_exists(public_path($produk->gambar))) {
                unlink(public_path($produk->gambar));
            }
        }

        DB::table('produk')->where('id_produk', $id_produk)->update([
            'nama_produk' => $request->nama_produk,
            'slug' => \Str::slug($request->nama_produk),
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'gambar' => $gambarPath,
            'id_kategori' => $request->id_kategori,
            'link_shopee' => $request->link_shopee,
            'link_tiktok' => $request->link_tiktok,
            'tanggal_update' => now(),
        ]);

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id_produk)
    {
        $produk = DB::table('produk')->where('id_produk', $id_produk)->first();
        if ($produk && $produk->gambar && file_exists(public_path($produk->gambar))) {
            unlink(public_path($produk->gambar));
        }

        DB::table('produk')->where('id_produk', $id_produk)->delete();
        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus!');
    }
}
