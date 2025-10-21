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

    public function uploadBukti(Request $request, $id)
    {
        $order = \App\Models\Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('metode_pembayaran', 'bank_transfer')
            ->firstOrFail();

        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // 📸 Upload bukti
        $file = $request->file('bukti_pembayaran');
        $filename = 'bukti_' . $order->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/bukti'), $filename);

        // 📝 Update order
        $order->update([
            'bukti_pembayaran' => $filename,
            'status_pembayaran' => 'menunggu_verifikasi'
        ]);

        return back()->with('success', '✅ Bukti pembayaran berhasil diupload, menunggu verifikasi admin.');
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
