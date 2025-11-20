<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\OrderItem;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'order_id'  => 'required',
            'id_produk' => 'required|exists:produk,id_produk',
            'rating'    => 'required|integer|min:1|max:5',
            'comment'   => 'required|string',
            'photos.*'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'video'     => 'nullable|mimes:mp4,mov,avi|max:10000'
        ]);

        // 1️⃣ Cek apakah produk ini ada dalam order
        $orderItemExists = OrderItem::where('order_id', $request->order_id)
            ->where('id_produk', $request->id_produk)
            ->exists();

        if (!$orderItemExists) {
            return back()->with('error', 'Produk ini tidak ditemukan dalam pesanan Anda.');
        }

        // 2️⃣ Cek apakah user sudah review produk ini dalam order ini
        $already = Review::where('user_id', auth()->id())
            ->where('id_produk', $request->id_produk)
            ->where('order_id', $request->order_id)
            ->exists();

        if ($already) {
            return back()->with('error', 'Anda sudah memberikan review untuk produk ini.');
        }

        // 3️⃣ Upload Foto
        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $filename = time().'_'.$photo->getClientOriginalName();
                $photo->move(public_path('uploads/reviews/photos'), $filename);
                $photoPaths[] = $filename;
            }
        }

        // 4️⃣ Upload Video
        $videoPath = null;
        if ($request->hasFile('video')) {
            $videoName = time().'_'.$request->video->getClientOriginalName();
            $request->video->move(public_path('uploads/reviews/videos'), $videoName);
            $videoPath = $videoName;
        }

        // 5️⃣ Simpan Review
        Review::create([
            'user_id' => auth()->id(),
            'id_produk' => $request->id_produk,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'photos' => $photoPaths ? json_encode($photoPaths) : null,
            'video' => $videoPath,
            'is_verified_purchase' => 1,
            'status' => 'approved'
        ]);

        return back()->with('success', 'Review berhasil dikirim!');
    }
}
