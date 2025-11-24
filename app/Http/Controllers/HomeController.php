<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        try {
            DB::connection()->getPdo();

            // Products
            $produk = collect([]);
            $produkTerbaru = collect([]);

            try {
                $produk = Produk::with('kategori')
                    ->where('status', 'aktif')
                    ->orderBy('created_at', 'desc')
                    ->take(6)
                    ->get();

                $produkTerbaru = $produk;
            } catch (\Exception $e) {
                Log::warning('Failed to load products', ['error' => $e->getMessage()]);
            }

            // News/Berita
            $berita = collect([]);
            $beritaTerbaru = collect([]);

            try {
                $berita = Berita::orderBy('tanggal', 'desc')
                    ->take(3)
                    ->get();

                $beritaTerbaru = $berita;
            } catch (\Exception $e) {
                Log::warning('Failed to load berita', ['error' => $e->getMessage()]);
            }

            return view('home', [
                'produk' => $produk,
                'produkTerbaru' => $produkTerbaru,
                'berita' => $berita,
                'beritaTerbaru' => $beritaTerbaru
            ]);

        } catch (\Exception $e) {
            Log::error('Homepage critical error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->view('home', [
                'produk' => collect([]),
                'produkTerbaru' => collect([]),
                'berita' => collect([]),
                'beritaTerbaru' => collect([])
            ], 500);
        }
    }
}
