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
        // Pastikan produk valid dan tersedia
        $produk = Produk::findOrFail($produkId);

        if ($produk->stok <= 0) {
            return back()->with('error', 'Produk ini sedang habis stok');
        }

        // Cek apakah produk sudah ada di keranjang user
        $cart = Cart::where('user_id', Auth::id())
            ->where('id_produk', $produkId)
            ->first();

        if ($cart) {
            // Jika sudah ada, tambahkan qty tapi jangan melebihi stok
            $newQty = $cart->qty + 1;
            if ($newQty > $produk->stok) {
                return back()->with('error', 'Jumlah melebihi stok tersedia');
            }
            $cart->increment('qty');
        } else {
            // Jika belum ada, buat baru
            Cart::create([
                'user_id' => Auth::id(),
                'id_produk' => $produkId,
                'qty' => 1,
            ]);
        }

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang 🛒');
    }

    /**
     * 🔄 Update jumlah produk di keranjang
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'qty' => 'required|integer|min:1',
        ]);

        $cart = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->with('produk')
            ->firstOrFail();

        if ($request->qty > $cart->produk->stok) {
            return back()->with('error', 'Jumlah melebihi stok tersedia');
        }

        $cart->update(['qty' => $request->qty]);

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
