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
            'nama' => 'required|string|max:100',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required_if:tipe_order,kirim|string|nullable',
            'catatan' => 'nullable|string',
        ]);

        if ($request->tipe_order === 'kirim') {
            return back()->with('error', 'Fitur pengiriman sedang dalam pengembangan 🚚✨');
        }

        $cartItems = \App\Models\Cart::where('user_id', auth()->id())->with('produk')->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('checkout.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        $total = $cartItems->sum(fn($item) => $item->qty * $item->produk->harga);

        DB::beginTransaction();
        try {
            $order = \App\Models\Order::create([
                'user_id' => auth()->id(),
                'nama' => $request->nama,
                'telepon' => $request->telepon,
                'alamat' => $request->tipe_order === 'ambil' ? 'Ambil di Toko' : $request->alamat,
                'catatan' => $request->catatan,
                'total' => $total,
                'tipe_order' => $request->tipe_order,
            ]);

            foreach ($cartItems as $item) {
                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'id_produk' => $item->produk->id_produk,
                    'qty' => $item->qty,
                    'harga' => $item->produk->harga,
                    'subtotal' => $item->qty * $item->produk->harga
                ]);

                $item->produk->decrement('stok', $item->qty);
            }

            \App\Models\Cart::where('user_id', auth()->id())->delete();

            DB::commit();
            return redirect()->route('checkout.index')->with('success', '✅ Pesanan berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }
}
