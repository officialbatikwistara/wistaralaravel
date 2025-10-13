<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Tambah ke keranjang
    public function addToCart(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        // jika user login
        $userId = Auth::check() ? Auth::id() : null;

        // cek apakah produk sudah ada di cart
        $cartItem = Cart::where('produk_id', $produk->id_produk)
                        ->where('user_id', $userId)
                        ->first();

        if ($cartItem) {
            $cartItem->jumlah += 1;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => $userId,
                'produk_id' => $produk->id_produk,
                'jumlah' => 1,
            ]);
        }

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang 🛒');
    }

    // Lihat keranjang
    public function index()
    {
        $userId = Auth::check() ? Auth::id() : null;
        $cartItems = Cart::with('produk')
                        ->where('user_id', $userId)
                        ->get();

        return view('cart.index', compact('cartItems'));
    }

    // Hapus item
    public function remove($id)
    {
        Cart::destroy($id);
        return back()->with('success', 'Produk dihapus dari keranjang 🗑️');
    }
}
