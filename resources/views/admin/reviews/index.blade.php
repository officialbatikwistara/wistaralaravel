@extends('layouts.admin')

@section('title', 'Moderasi Review')

@section('content')

<style>
/* 🌄 Background fullscreen solid */
body {
  font-family: 'Poppins', sans-serif;
  color: #0b1841;
  margin: 0;
  min-height: 100vh;
  background: url('{{ asset('img/background1.svg') }}') no-repeat center center fixed;
  background-size: cover;
}

/* Judul Halaman */
h1 {
  font-weight: 700;
  color: #0b1841;
}

/* 🌟 Container tabel */
.table-container {
  background: rgba(255, 255, 255, 0.97); /* lebih solid tapi tetap elegan */
  border-radius: 18px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  overflow: hidden;
  margin-top: 20px;
  backdrop-filter: blur(6px);
}

/* 🧭 Header tabel */
.table thead.table-header th {
  background-color: #081738 !important;
  color: #ffffff !important;
  font-weight: 600 !important;
  font-size: 15px !important;
  border: none !important;
  padding: 16px !important;
  text-align: center !important;
}

/* Membulatkan header */
.table-container table {
  border-collapse: separate !important;
  border-spacing: 0 !important;
  border-radius: 18px !important;
  overflow: hidden !important;
}

.table-container thead th:first-child {
  border-top-left-radius: 18px !important;
}

.table-container thead th:last-child {
  border-top-right-radius: 18px !important;
}

/* 📦 Baris review */
.review-row {
  border-bottom: 1px solid #e5e7eb;
  transition: 0.25s ease;
}

.review-row:hover {
  background-color: #f9fafb;
  transform: scale(1.002);
}

/* 📋 Isi tabel */
.review-cell {
  padding: 20px 16px;
  vertical-align: middle;
}

/* 🏷️ Rating badge */
.rating-badge {
  background-color: #fbbf24 !important;
  color: #333 !important;
}

/* Status badges */
.status-pending {
  background-color: #fbbf24 !important;
  color: #333 !important;
}

.status-approved {
  background-color: #10b981 !important;
}

.status-rejected {
  background-color: #dc2626 !important;
}

/* ✏️ Tombol aksi */
.btn-action {
  border: none;
  padding: 8px 12px;
  border-radius: 8px;
  color: white;
  transition: 0.3s ease;
}

.btn-approve {
  background-color: #10b981;
}

.btn-approve:hover {
  background-color: #059669;
}

.btn-reject {
  background-color: #fbbf24;
}

.btn-reject:hover {
  background-color: #d1a106;
}

.btn-delete {
  background-color: #dc2626;
}

.btn-delete:hover {
  background-color: #b91c1c;
}

/* 🧩 Tombol utama */
.btn-dark {
  background-color: #081738 !important;
  border: none !important;
  border-radius: 12px !important;
  font-weight: 600 !important;
}

.btn-dark:hover {
  background-color: #152a6e !important;
}

/* Filter card */
.card {
  background: rgba(255, 255, 255, 0.95);
  border: none;
  border-radius: 18px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}
</style>

{{-- =========================
    🌸 MAIN CONTENT
========================= --}}
<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Moderasi Review</h1>
  </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success mb-3">
            {{ $message }}
        </div>
    @endif

    <!-- Filter & Search -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reviews.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Cari</label>
                    <input type="text" name="search" placeholder="Nama user atau komentar..." value="{{ request('search') }}" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Urutkan</label>
                    <select name="sort_by" class="form-select">
                        <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Terbaru</option>
                        <option value="rating" {{ request('sort_by') === 'rating' ? 'selected' : '' }}>Rating</option>
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2 align-items-end">
                    <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="table-container">
        <table class="table align-middle text-center">
            <thead class="table-header">
                <tr>
                    <th>User</th>
                    <th>Produk</th>
                    <th>Rating</th>
                    <th>Komentar</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reviews as $review)
                <tr class="review-row">
                    <td class="review-cell">
                        <strong>{{ $review->user->name }}</strong>
                    </td>
                    <td class="review-cell">
                        {{ $review->product->nama_produk ?? 'N/A' }}
                    </td>
                    <td class="review-cell">
                        <span class="badge rating-badge">
                            ⭐ {{ $review->rating }}/5
                        </span>
                    </td>
                    <td class="review-cell">
                        <div class="text-truncate" style="max-width: 300px;">
                            {{ $review->komentar }}
                        </div>
                    </td>
                    <td class="review-cell">
                        @if ($review->status === 'pending')
                            <span class="badge status-pending">Pending</span>
                        @elseif ($review->status === 'approved')
                            <span class="badge status-approved">Disetujui</span>
                        @else
                            <span class="badge status-rejected">Ditolak</span>
                        @endif
                    </td>
                    <td class="review-cell fw-semibold">
                        {{ $review->created_at->format('d M Y') }}
                    </td>
                    <td class="review-cell text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('admin.reviews.edit', $review->id) }}" class="btn btn-action btn-edit" title="Edit Review">
                                <i class="fa-solid fa-pen"></i>
                            </a>

                            @if ($review->status !== 'approved')
                                <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-action btn-approve" title="Setujui Review">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                </form>
                            @endif

                            @if ($review->status !== 'rejected')
                                <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-action btn-reject" title="Tolak Review">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('admin.reviews.delete', $review->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus review ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-action btn-delete" title="Hapus Review">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="fa-solid fa-folder-open fa-2x mb-2"></i><br>
                        Belum ada review
                    </td>
                </tr>
                @endforelse
            </tbody>
            </table>

        </table>

        {{-- PAGINATION --}}
        @if($reviews->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $reviews->links() }}
        </div>
        @endif
    </div>
@endsection
