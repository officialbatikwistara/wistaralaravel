<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserOrderController extends Controller
{
    // 📄 Halaman detail pesanan user
    public function show($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['items.produk'])
            ->firstOrFail();

        return view('user.pesanan.show', compact('order'));
    }

    // ❌ Pembatalan pesanan (kembalikan stok)
    public function cancel($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending') // hanya pesanan pending yang bisa dibatalkan
            ->with('items.produk')
            ->firstOrFail();

        DB::beginTransaction();
        try {
            // 🛍️ Kembalikan stok
            foreach ($order->items as $item) {
                $item->produk->increment('stok', $item->qty);
            }

            // ❌ Ubah status jadi batal
            $order->update([
                'status' => 'batal'
            ]);

            DB::commit();
            return redirect()->route('user.order.show', $id)->with('success', 'Pesanan berhasil dibatalkan dan stok dikembalikan ✅');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan pesanan: '.$e->getMessage());
        }
    }
}
