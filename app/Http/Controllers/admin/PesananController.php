<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{

    /**
     * Menampilkan daftar semua pesanan (halaman utama admin)
     * Bisa difilter berdasarkan:
     * - nama / id pesanan (search)
     * - rentang tanggal (start_date & end_date)
     * Dan juga bisa menampilkan detail pesanan di bawah tabel
     */
   public function index(Request $request)
    {
        // 1️⃣ Mulai query dari model Order
        $pesanan = Order::query();

        // 2️⃣ Jika ada input pencarian (misal nama pelanggan atau ID order)
        if ($request->search) {
            $pesanan->where('nama', 'like', "%{$request->search}%")
                    ->orWhere('id', 'like', "%{$request->search}%");
        }

        // 3️⃣ Jika ada filter tanggal awal & akhir
        if ($request->start_date && $request->end_date) {
            $pesanan->whereBetween('created_at', [
                $request->start_date,                     // tanggal mulai
                $request->end_date . ' 23:59:59',         // tanggal akhir + jam maksimal
            ]);
        }

        // 4️⃣ Urutkan pesanan dari yang terbaru dan tampilkan 10 per halaman
        $orders = $pesanan->orderBy('created_at', 'desc')->paginate(10);

        // 5️⃣ Jika ada parameter `detail_id`, berarti admin klik tombol “Lihat Detail”
        // Maka ambil data detail pesanan tersebut beserta item produknya
        $detail = null;
        if ($request->has('detail_id')) {
            $detail = Order::with('items.product')->find($request->detail_id);
        }

        // 6️⃣ Kirim data pesanan & detail ke view `admin/pesanan/index.blade.php`
        return view('admin.pesanan.index', compact('orders', 'detail'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * (Opsional) Halaman detail pesanan terpisah.
     * Ini hanya dipakai kalau kamu ingin detail di halaman lain, bukan di bawah tabel.
     */
    public function show(string $id)
    {
        // Ambil data pesanan beserta item produknya
        $order = Order::with('items.product')->findOrFail($id);

        // Kirim ke view `admin/pesanan/show.blade.php`
        return view('admin.pesanan.index', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
