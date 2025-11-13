@include('inc.header')

<div class="dashboard-page" style="padding-top: 120px; background: url('{{ asset('img/bghero.svg') }}') center/cover no-repeat;">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">

                <!-- 👤 Header -->
                <div class="card shadow-lg rounded-4 p-4 text-center border-0 mb-4">
                    <i class="fa-solid fa-heart fa-3x text-warning mb-3"></i>
                    <h2 class="fw-bold mb-2 text-dark">Wishlist Saya</h2>
                    <p class="text-muted mb-0">Produk yang Anda simpan untuk dibeli nanti</p>
                </div>

                <!-- 📦 Daftar Wishlist -->
                <div class="card shadow-lg rounded-4 p-4 border-0">
                    <h4 class="fw-bold mb-4">
                        <i class="fa-solid fa-heart me-2 text-dark"></i> Produk Favorit
                    </h4>

                    @if ($wishlists->count() > 0)
                        <!-- 💻 Tabel Desktop -->
                        <div class="table-responsive d-none d-md-block mb-4">
                            <table class="table align-middle">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th>Produk</th>
                                        <th class="text-center">Harga</th>
                                        <th class="text-center">Tanggal Ditambahkan</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($wishlists as $wishlist)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @php
                                                        $fileName = basename($wishlist->product->gambar ?? '');
                                                        $gambarPath = public_path('uploads/produk/'.$fileName);
                                                        $gambarUrl = (file_exists($gambarPath) && $fileName)
                                                            ? asset('uploads/produk/'.$fileName)
                                                            : asset('img/logo.png');
                                                    @endphp
                                                    <img src="{{ $gambarUrl }}" alt="{{ $wishlist->product->nama_produk }}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                    <div>
                                                        <strong class="text-dark">{{ $wishlist->product->nama_produk }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ Str::limit($wishlist->product->deskripsi, 50) }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-warning text-dark">Rp {{ number_format($wishlist->product->harga, 0, ',', '.') }}</span>
                                            </td>
                                            <td class="text-center text-muted small">
                                                {{ $wishlist->created_at->format('d M Y') }}
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('produk.show', $wishlist->product->slug) }}" class="btn btn-outline-primary">
                                                        <i class="fa-solid fa-eye me-1"></i> Lihat
                                                    </a>
                                                    <button onclick="removeFromWishlist({{ $wishlist->id_produk }})" class="btn btn-outline-danger">
                                                        <i class="fa-solid fa-trash me-1"></i> Hapus
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- 📱 Kartu Mobile -->
                        <div class="d-block d-md-none">
                            @foreach ($wishlists as $wishlist)
                                <div class="border rounded-4 p-3 mb-3 shadow-sm bg-light">
                                    <div class="d-flex align-items-start mb-2">
                                        @php
                                            $fileName = basename($wishlist->product->gambar ?? '');
                                            $gambarPath = public_path('uploads/produk/'.$fileName);
                                            $gambarUrl = (file_exists($gambarPath) && $fileName)
                                                ? asset('uploads/produk/'.$fileName)
                                                : asset('img/logo.png');
                                        @endphp
                                        <img src="{{ $gambarUrl }}" alt="{{ $wishlist->product->nama_produk }}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-1">{{ $wishlist->product->nama_produk }}</h6>
                                            <p class="text-warning fw-bold mb-1">Rp {{ number_format($wishlist->product->harga, 0, ',', '.') }}</p>
                                            <small class="text-muted">{{ $wishlist->created_at->format('d M Y') }}</small>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <a href="{{ route('produk.show', $wishlist->product->slug) }}" class="btn btn-primary btn-sm flex-fill rounded-pill">
                                            <i class="fa-solid fa-eye me-1"></i> Lihat Produk
                                        </a>
                                        <button onclick="removeFromWishlist({{ $wishlist->id_produk }})" class="btn btn-danger btn-sm flex-fill rounded-pill">
                                            <i class="fa-solid fa-trash me-1"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fa-solid fa-heart fa-3x text-warning mb-3"></i>
                            <h5 class="text-muted mb-2">Wishlist Kosong</h5>
                            <p class="text-muted mb-4">Anda belum menambahkan produk apapun ke wishlist.</p>
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

<script>
function removeFromWishlist(productId) {
    if (confirm('Apakah Anda yakin ingin menghapus produk ini dari wishlist?')) {
        fetch(`/wishlist/remove/${productId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            location.reload();
        })
        .catch(error => {
            alert('Terjadi kesalahan saat menghapus produk dari wishlist.');
        });
    }
}
</script>

@include('inc.footer')
