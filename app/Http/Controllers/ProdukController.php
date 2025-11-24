<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Produk::where('status', 'aktif');

            // Search
            if ($request->has('search')) {
                $query->where('nama_produk', 'like', '%' . $request->search . '%');
            }

            // Category filter
            if ($request->has('kategori')) {
                $query->where('kategori_id', $request->kategori);
            }

            // Sorting
            $sort = $request->get('sort', 'terbaru');
            switch ($sort) {
                case 'termurah':
                    $query->orderBy('harga', 'asc');
                    break;
                case 'termahal':
                    $query->orderBy('harga', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }

            $produk = $query->paginate(12);
            $kategoris = Kategori::all();

            return view('produk.index', compact('produk', 'kategoris'));
        } catch (\Exception $e) {
            \Log::error('Produk index error: ' . $e->getMessage());
            return response()->view('errors.500', [], 500);
        }
    }

    public function show($slug)
    {
        try {
            $produk = Produk::where('slug', $slug)
                ->where('status', 'aktif')
                ->firstOrFail();

            $related = Produk::where('kategori_id', $produk->kategori_id)
                ->where('id_produk', '!=', $produk->id_produk)
                ->where('status', 'aktif')
                ->take(4)
                ->get();

            return view('produk.show', compact('produk', 'related'));
        } catch (\Exception $e) {
            \Log::error('Produk show error: ' . $e->getMessage());
            abort(404);
        }
    }
}
