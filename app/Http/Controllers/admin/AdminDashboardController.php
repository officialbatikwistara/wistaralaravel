<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Cek session admin
        if (!session()->has('admin_logged_in')) {
            return redirect()->route('admin.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        /* =============================
           TOP STATISTIC
        ============================== */

        $totalProduk = DB::table('produk')->count();

        $totalOrder = DB::table('orders')->count();

        $pendapatan = DB::table('orders')
            ->where('status', 'selesai')
            ->sum('final_total');

        /* =============================
           GRAFIK PENJUALAN BULANAN
        ============================== */

        $penjualanBulanan = DB::table('orders')
            ->selectRaw("MONTHNAME(created_at) as bulan, SUM(final_total) as total")
            ->where('status', 'selesai')
            ->groupBy('bulan')
            ->orderByRaw("MIN(created_at)")
            ->get();

        $bulan = $penjualanBulanan->pluck('bulan');
        $penjualan = $penjualanBulanan->pluck('total');

        /* =============================
           PRODUK TERLARIS
        ============================== */

        $produkTerlaris = DB::table('order_items')
            ->join('produk', 'order_items.id_produk', '=', 'produk.id_produk')
            ->select('produk.nama_produk', DB::raw('SUM(order_items.qty) as total_jual'))
            ->groupBy('produk.nama_produk')
            ->orderBy('total_jual', 'DESC')
            ->limit(5)
            ->get();

        $namaProdukTerlaris = $produkTerlaris->pluck('nama_produk');
        $jumlahTerjual = $produkTerlaris->pluck('total_jual');

        /* =============================
           PESANAN TERBARU (5 transaksi)
        ============================== */

        $orderBaru = DB::table('orders')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProduk',
            'totalOrder',
            'pendapatan',
            'bulan',
            'penjualan',
            'namaProdukTerlaris',
            'jumlahTerjual',
            'orderBaru'
        ));
    }
}
