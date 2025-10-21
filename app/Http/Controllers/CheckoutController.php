<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use DB;

class CheckoutController extends Controller
{
    // 🟡 1. Halaman checkout
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('produk')->get();
        $total = $cartItems->sum(fn($item) => $item->qty * $item->produk->harga);

        return view('checkout.index', compact('cartItems', 'total'));
    }

    // 🟢 2. Proses checkout
    public function process(Request $request)
    {
        $request->validate([
            'tipe_order' => 'required|in:ambil,kirim',
            'metode_pembayaran' => 'required|in:bank_transfer,qris,cod',
            'nama' => 'required|string|max:100',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required_if:tipe_order,kirim|string|nullable',
            'catatan' => 'nullable|string',
            'tanggal_ambil' => 'required|date',
        ]);

        // 🚧 Fitur kirim belum aktif
        if ($request->tipe_order === 'kirim') {
            return back()->with('error', 'Fitur pengiriman sedang dalam pengembangan 🚚✨');
        }

        // Ambil data keranjang
        $cartItems = Cart::where('user_id', auth()->id())->with('produk')->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('checkout.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        $total = $cartItems->sum(fn($item) => $item->qty * $item->produk->harga);

        DB::beginTransaction();
        try {
            // 📝 Buat pesanan
            $order = Order::create([
                'user_id' => auth()->id(),
                'nama' => $request->nama,
                'telepon' => $request->telepon,
                'alamat' => $request->tipe_order === 'ambil' ? 'Ambil di Toko' : $request->alamat,
                'catatan' => $request->catatan,
                'total' => $total,
                'tipe_order' => $request->tipe_order,
                'metode_pembayaran' => $request->metode_pembayaran,
                'tanggal_ambil' => $request->tanggal_ambil,
                'status' => 'pending',
                'status_pembayaran' => 'belum_bayar'
            ]);

            // 💼 Simpan detail item pesanan
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'id_produk' => $item->produk->id_produk,
                    'qty' => $item->qty,
                    'harga' => $item->produk->harga,
                    'subtotal' => $item->qty * $item->produk->harga
                ]);

                // Kurangi stok produk
                $item->produk->decrement('stok', $item->qty);
            }

            // 🧹 Hapus keranjang setelah checkout
            Cart::where('user_id', auth()->id())->delete();

            DB::commit();

            if ($request->metode_pembayaran === 'cod') {
                // COD langsung ke dashboard user
                return redirect('/user/dashboard')->with('success', '✅ Pesanan COD berhasil dibuat. Silakan ambil di toko.');
            } elseif ($request->metode_pembayaran === 'bank_transfer') {
                return redirect()->route('checkout.bank', $order->id);
            } elseif ($request->metode_pembayaran === 'qris') {
                return redirect()->route('checkout.qris', $order->id);
            }

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }
}
