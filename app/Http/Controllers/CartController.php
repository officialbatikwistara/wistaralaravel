<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('produk')->get();
        return view('cart.index', compact('cartItems'));
    }

    public function add(Request $request, $produkId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login dulu untuk menambahkan ke keranjang');
        }

        $cartItem = Cart::where('user_id', Auth::id())
                        ->where('produk_id', $produkId)
                        ->first();

        if ($cartItem) {
            $cartItem->increment('jumlah');
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'produk_id' => $produkId,
                'jumlah' => 1
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function remove($id)
    {
        $cartItem = Cart::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $cartItem->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang');
    }
}
