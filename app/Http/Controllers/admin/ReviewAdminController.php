<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewAdminController extends Controller
{
    // GET /admin/reviews
    public function index(Request $request)
    {
        $query = Review::with(['user:id,name', 'product:id_produk,nama_produk']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan produk
        if ($request->filled('product_id')) {
            $query->where('id_produk', $request->product_id);
        }

        // Search berdasarkan nama user atau komentar
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhere('komentar', 'like', "%{$search}%");
        }

        // Sorting
        $sortBy = in_array($request->get('sort_by'), ['created_at', 'rating']) ? $request->get('sort_by') : 'created_at';
        $sortDir = $request->get('sort_dir') === 'asc' ? 'asc' : 'desc';

        $reviews = $query->orderBy($sortBy, $sortDir)->paginate(15);

        return view('admin.reviews.index', compact('reviews'));
    }

    // PATCH /admin/reviews/{id}/approve
    public function approve($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['status' => 'approved']);

        return redirect()->route('admin.reviews.index')->with('success', 'Review disetujui.');
    }

    // PATCH /admin/reviews/{id}/reject
    public function reject($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['status' => 'rejected']);

        return redirect()->route('admin.reviews.index')->with('success', 'Review ditolak.');
    }

    // GET /admin/reviews/{id}/edit
    public function edit($id)
    {
        $review = Review::with(['user:id,name', 'product:id_produk,nama_produk'])->findOrFail($id);
        return view('admin.reviews.edit', compact('review'));
    }

    // PATCH /admin/reviews/{id}
    public function update(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|max:1000',
        ]);

        $review = Review::findOrFail($id);
        $review->update([
            'rating' => $request->rating,
            'komentar' => $request->komentar,
        ]);

        return redirect()->route('admin.reviews.index')->with('success', 'Review berhasil diperbarui.');
    }

    // DELETE /admin/reviews/{id}
    public function destroy($id)
    {
        $review = Review::findOrFail($id);

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

        return redirect()->route('admin.reviews.index')->with('success', 'Review dihapus.');
    }
}
