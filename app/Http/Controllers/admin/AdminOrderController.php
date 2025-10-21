<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class AdminOrderController extends Controller
{
    // 📝 Tampilkan semua pesanan
    public function index()
    {
        // ✅ Ambil semua pesanan tanpa Auth user
        $orders = Order::orderBy('created_at', 'desc')->get();

        return view('admin.pesanan.index', compact('orders'));
    }

    // 📄 Detail pesanan
    public function show($id)
    {
        $order = Order::with(['items.produk'])->findOrFail($id);
        return view('admin.pesanan.show', compact('order'));
    }

    // 💳 Update status pembayaran
    public function updatePayment(Request $request, $id)
    {
        $request->validate([
            'status_pembayaran' => 'required|in:belum_bayar,menunggu_verifikasi,lunas,gagal',
            'status' => 'nullable|in:pending,proses,selesai,batal'
        ]);

        $order = Order::findOrFail($id);
        $order->status_pembayaran = $request->status_pembayaran;

        if ($request->status) {
            $order->status = $request->status;
        }

        $order->save();

        return back()->with('success', '✅ Status pembayaran pesanan berhasil diperbarui.');
    }
}
