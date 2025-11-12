<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller as BaseController;

class UserReviewController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // GET /user/reviews
    public function index(Request $request)
    {
        $query = Review::where('user_id', auth()->id())
            ->with(['product:id_produk,nama_produk']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('user.reviews.index', compact('reviews'));
    }

    // GET /user/reviews/{id}/edit
    public function edit($id)
    {
        $review = Review::findOrFail($id);

        if ($review->user_id !== auth()->id()) {
            return redirect()->route('user.reviews.index')->with('error', 'Unauthorized');
        }

        return view('user.reviews.edit', compact('review'));
    }

    // PATCH /user/reviews/{id}
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        if ($review->user_id !== auth()->id()) {
            return redirect()->route('user.reviews.index')->with('error', 'Unauthorized');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string',
        ]);

        $review->update([
            'rating' => $validated['rating'],
            'komentar' => $validated['komentar'],
        ]);

        return redirect()->route('user.reviews.index')->with('success', 'Review diperbarui.');
    }

    // DELETE /user/reviews/{id}
    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        if ($review->user_id !== auth()->id()) {
            return redirect()->route('user.reviews.index')->with('error', 'Unauthorized');
        }

        // Hapus media
        if ($review->photos) {
            foreach ($review->photos as $path) {
                Storage::disk('public')->delete($path);
            }
        }
        if ($review->video) {
            Storage::disk('public')->delete($review->video);
        }

        $review->delete();

        return redirect()->route('user.reviews.index')->with('success', 'Review dihapus.');
    }
}
