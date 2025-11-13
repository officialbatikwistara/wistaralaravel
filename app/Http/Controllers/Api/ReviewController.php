<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\ReviewHelpfulVote;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    // GET /api/reviews
    public function index(Request $request)
    {
        $query = Review::with(['user:id,name', 'product:id_produk,nama_produk']);

        // Hanya tampilkan approved jika bukan admin
        if (!$request->user() || !($request->user()->is_admin ?? false)) {
            $query->where('status', 'approved');
        }

        if ($request->filled('product_id')) {
            $query->where('id_produk', $request->product_id);
        }

        $reviews = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }

    // POST /api/reviews
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_produk' => 'required|exists:produk,id_produk',
            'order_id' => 'nullable|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
            'photos.*' => 'nullable|image|max:2048',
            'video' => 'nullable|mimes:mp4,mov,avi,webm|max:20480',
        ]);

        $review = new Review();
        $review->id_produk = $validated['id_produk'];
        $review->order_id = $validated['order_id'] ?? null;
        $review->user_id = $request->user()->id;
        $review->rating = $validated['rating'];
        $review->comment = $validated['comment'];
        $review->status = 'pending';

        // Simpan foto
        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photos[] = $photo->store('reviews/photos', 'public');
            }
        }
        if (!empty($photos)) {
            $review->photos = json_encode($photos);
        }

        // Simpan video
        if ($request->hasFile('video')) {
            $review->video = $request->file('video')->store('reviews/videos', 'public');
        }

        $review->save();

        // Notify admin about new review
        $admin = \App\Models\Admin::first(); // Assuming there's at least one admin
        if ($admin) {
            $admin->notify(new \App\Notifications\ReviewNotification($review->load('product'), $request->user()));
        }

        return response()->json([
            'message' => 'Review berhasil ditambahkan dan menunggu persetujuan.',
            'data' => $review->load(['user:id,name'])
        ], 201);
    }

    // GET /api/reviews/{id}
    public function show($id)
    {
        $review = Review::with(['user:id,name', 'product:id_produk,nama_produk'])->findOrFail($id);
        return response()->json($review);
    }

    // PATCH /api/reviews/{id}
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        // Hanya pemilik yang bisa ubah isi; admin bisa ubah status
        $isOwner = $request->user() && $review->user_id === $request->user()->id;
        $isAdmin = $request->user() && method_exists($request->user(), 'isAdmin') ? $request->user()->isAdmin() : ($request->user()->is_admin ?? false);

        $rules = [];
        if ($isOwner) {
            $rules = [
                'rating' => 'nullable|integer|min:1|max:5',
                'comment' => 'nullable|string',
            ];
        }
        if ($isAdmin) {
            $rules['status'] = 'nullable|in:pending,approved,rejected';
            $rules['reply'] = 'nullable|string';
            $rules['is_verified_purchase'] = 'nullable|boolean';
        }

        if (empty($rules)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate($rules);
        $review->fill($validated);

        // Set replied_at if reply is provided
        if (isset($validated['reply']) && $validated['reply']) {
            $review->replied_at = now();
        }

        $review->save();

        return response()->json(['message' => 'Review diperbarui.', 'data' => $review]);
    }

    // DELETE /api/reviews/{id}
    public function destroy(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        $isOwner = $request->user() && $review->user_id === $request->user()->id;
        $isAdmin = $request->user() && ($request->user()->is_admin ?? false);

        if (!$isOwner && !$isAdmin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Hapus media bila ada
        if ($review->photos) {
            foreach (json_decode($review->photos, true) as $path) {
                Storage::disk('public')->delete($path);
            }
        }
        if ($review->video) {
            Storage::disk('public')->delete($review->video);
        }

        $review->delete();

        return response()->json(['message' => 'Review dihapus.']);
    }

    // POST /api/reviews/{id}/helpful
    public function markHelpful(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        if (!$request->user()) {
            return response()->json(['message' => 'Authentication required'], 401);
        }

        $userId = $request->user()->id;

        // Check if user already voted
        if ($review->hasUserVotedHelpful($userId)) {
            return response()->json(['message' => 'Already voted helpful'], 400);
        }

        // Create vote
        ReviewHelpfulVote::create([
            'review_id' => $review->id,
            'user_id' => $userId
        ]);

        // Update helpful count
        $review->increment('helpful_count');

        return response()->json([
            'message' => 'Marked as helpful',
            'helpful_count' => $review->helpful_count
        ]);
    }
}

