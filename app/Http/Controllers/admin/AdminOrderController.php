<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        // filter
        $keyword = $request->keyword;
        $start = $request->start;
        $end = $request->end;

        $orders = Order::query();

        if ($keyword) {
            $orders->where(function($q) use ($keyword) {
                $q->where('nama', 'like', "%$keyword%")
                  ->orWhere('id', 'like', "%$keyword%");
            });
        }

        if ($start && $end) {
            $orders->whereBetween('created_at', [$start.' 00:00:00', $end.' 23:59:59']);
        }

        $orders = $orders->orderBy('created_at', 'desc')->get();

        return view('admin.pesanan.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('items.produk')->findOrFail($id);
        return view('admin.pesanan.show', compact('order'));
    }

    public function updatePayment(Request $request, $id)
    {
        $request->validate([
            'status_pembayaran' => 'required|in:belum_bayar,menunggu_verifikasi,lunas,gagal'
        ]);

        $order = \App\Models\Order::findOrFail($id);
        $order->status_pembayaran = $request->status_pembayaran;
        $order->save();

        return back()->with('success', '✅ Status pembayaran berhasil diperbarui.');
    }

}
