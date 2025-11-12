<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Produk;

class CartController extends Controller
{
    /**
     * 🧺 Menampilkan daftar keranjang user
     */
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('produk')
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->qty * $item->produk->harga;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * ➕ Tambahkan produk ke keranjang
     */
    public function add(Request $request, $produkId)
    {
        // Pastikan produk valid
        $produk = Produk::findOrFail($produkId);

        // qty dari request (minimal 1)
        $qty = max((int) $request->input('qty', 1), 1);

        // Cek stok
        if ($qty > $produk->stok) {
            return back()->with('error', 'Jumlah melebihi stok produk.');
        }

        // Cek apakah produk sudah ada di keranjang user (pakai id_produk sesuai skema DB)
        $cart = Cart::where('user_id', Auth::id())
            ->where('id_produk', $produkId)
            ->first();

        if ($cart) {
            // Tambahkan qty tapi jangan melebihi stok
            $newQty = min($cart->qty + $qty, $produk->stok);
            $cart->update([
                'qty' => $newQty,
                'updated_at' => now(),
            ]);
        } else {
            // Jika belum ada, buat baru
            Cart::create([
                'user_id'   => Auth::id(),
                'id_produk' => $produkId,
                'qty'       => $qty,
                'created_at'=> now(),
                'updated_at'=> now(),
            ]);
        }

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang 🛒');
    }

    /**
     * 🔄 Update jumlah produk di keranjang
     */
    public function update(Request $request, $id)
    {
        // Validasi dasar seperti di main
        $request->validate([
            'qty' => 'required|integer|min:1',
        ]);

        // Ambil item keranjang milik user
        $cart = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Pastikan tidak melebihi stok produk terkait
        $produk = Produk::find($cart->id_produk);
        if ($produk && $request->qty > $produk->stok) {
            return back()->with('error', 'Jumlah melebihi stok produk.');
        }

        $cart->update([
            'qty' => (int) $request->qty,
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Jumlah produk berhasil diperbarui ✅');
    }

    /**
     * ❌ Hapus item dari keranjang
     */
    public function remove($id)
    {
        $cart = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $cart->delete();

        return back()->with('success', 'Produk berhasil dihapus dari keranjang ❎');
    }
}