<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class WishlistController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // GET /wishlist
    public function index()
    {
        $wishlists = Wishlist::where('user_id', auth()->id())
            ->with(['product:id_produk,nama_produk,harga,gambar'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.wishlist.index', compact('wishlists'));
    }

    // POST /wishlist/add/{productId}
    public function add($productId)
    {
        $existing = Wishlist::where('user_id', auth()->id())
            ->where('id_produk', $productId)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Already in wishlist'], 400);
        }

        Wishlist::create([
            'user_id' => auth()->id(),
            'id_produk' => $productId
        ]);

        return response()->json(['message' => 'Added to wishlist']);
    }

    // DELETE /wishlist/remove/{productId}
    public function remove($productId)
    {
        Wishlist::where('user_id', auth()->id())
            ->where('id_produk', $productId)
            ->delete();

        return response()->json(['message' => 'Removed from wishlist']);
    }

    // GET /wishlist/check/{productId}
    public function check($productId)
    {
        $exists = Wishlist::where('user_id', auth()->id())
            ->where('id_produk', $productId)
            ->exists();

        return response()->json(['in_wishlist' => $exists]);
    }
}
