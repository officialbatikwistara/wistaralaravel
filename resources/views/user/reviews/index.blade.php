@include('inc.header')

<div class="dashboard-page" style="padding-top: 120px; background: url('{{ asset('img/bghero.svg') }}') center/cover no-repeat;">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">

                <!-- 👤 Header -->
                <div class="card shadow-lg rounded-4 p-4 text-center border-0 mb-4">
                    <i class="fa-solid fa-star fa-3x text-warning mb-3"></i>
                    <h2 class="fw-bold mb-2 text-dark">Review Saya</h2>
                    <p class="text-muted mb-0">Kelola semua ulasan produk yang telah Anda berikan</p>
                </div>

                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- 🔍 Filter -->
                <div class="card shadow-lg rounded-4 p-4 border-0 mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fa-solid fa-filter me-2 text-dark"></i> Filter Review
                    </h5>
                    <form method="GET" action="{{ route('user.reviews.index') }}" class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Status Review</label>
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>✅ Disetujui</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>❌ Ditolak</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-dark flex-fill rounded-pill">
                                <i class="fa-solid fa-search me-2"></i> Filter
                            </button>
                            <a href="{{ route('user.reviews.index') }}" class="btn btn-outline-dark rounded-pill">
                                <i class="fa-solid fa-rotate-right me-2"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>

                <!-- 📝 Daftar Review -->
                <div class="card shadow-lg rounded-4 p-4 border-0">
                    <h4 class="fw-bold mb-4">
                        <i class="fa-solid fa-list me-2 text-dark"></i> Daftar Review
                    </h4>

                    @if ($reviews->count() > 0)
                        <!-- 💻 Tabel Desktop -->
                        <div class="table-responsive d-none d-md-block mb-4">
                            <table class="table align-middle">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th>Produk</th>
                                        <th class="text-center">Rating</th>
                                        <th>Komentar</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Tanggal</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reviews as $review)
                                        <tr>
                                            <td>
                                                <strong class="text-dark">{{ $review->product->nama_produk ?? 'N/A' }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-warning text-dark">
                                                    ⭐ {{ $review->rating }}/5
                                                </span>
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 300px;">
                                                    {{ $review->komentar }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if ($review->status === 'pending')
                                                    <span class="badge bg-warning text-dark">⏳ Pending</span>
                                                @elseif ($review->status === 'approved')
                                                    <span class="badge bg-success">✅ Disetujui</span>
                                                @else
                                                    <span class="badge bg-danger">❌ Ditolak</span>
                                                @endif
                                            </td>
                                            <td class="text-center text-muted small">
                                                {{ $review->created_at->format('d M Y') }}
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('user.reviews.edit', $review->id) }}" class="btn btn-outline-primary">
                                                        <i class="fa-solid fa-edit me-1"></i> Edit
                                                    </a>
                                                    <form action="{{ route('user.reviews.destroy', $review->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus review ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger">
                                                            <i class="fa-solid fa-trash me-1"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- 📱 Kartu Mobile -->
                        <div class="d-block d-md-none">
                            @foreach ($reviews as $review)
                                <div class="border rounded-4 p-3 mb-3 shadow-sm bg-light">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="fw-bold mb-0">{{ $review->product->nama_produk ?? 'N/A' }}</h6>
                                        <small class="text-muted">{{ $review->created_at->format('d M Y') }}</small>
                                    </div>

                                    <div class="mb-2">
                                        <span class="badge bg-warning text-dark me-2">
                                            ⭐ {{ $review->rating }}/5
                                        </span>
                                        @if ($review->status === 'pending')
                                            <span class="badge bg-warning text-dark">⏳ Pending</span>
                                        @elseif ($review->status === 'approved')
                                            <span class="badge bg-success">✅ Disetujui</span>
                                        @else
                                            <span class="badge bg-danger">❌ Ditolak</span>
                                        @endif
                                    </div>

                                    <p class="mb-3 text-secondary">{{ $review->komentar }}</p>

                                    <div class="d-flex gap-2">
                                        <a href="{{ route('user.reviews.edit', $review->id) }}" class="btn btn-primary btn-sm flex-fill rounded-pill">
                                            <i class="fa-solid fa-edit me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('user.reviews.destroy', $review->id) }}" method="POST" class="flex-fill" onsubmit="return confirm('Yakin ingin menghapus review ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm w-100 rounded-pill">
                                                <i class="fa-solid fa-trash me-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $reviews->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fa-solid fa-star-half-alt fa-3x text-warning mb-3"></i>
                            <h5 class="text-muted mb-2">Belum Ada Review</h5>
                            <p class="text-muted mb-4">Anda belum memberikan review untuk produk manapun.</p>
                            <a href="{{ route('katalog') }}" class="btn btn-dark rounded-pill px-4">
                                <i class="fa-solid fa-store me-2"></i> Jelajahi Produk
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@include('inc.footer')
