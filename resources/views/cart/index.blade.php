@include('inc.header')

<div class="dashboard-page" style="padding-top: 120px; background: url('{{ asset('img/bghero.svg') }}') center/cover no-repeat;">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">

                <!-- 👤 Header -->
                <div class="card shadow-lg rounded-4 p-4 text-center border-0 mb-4">
                    <i class="fa-solid fa-cart-shopping fa-3x text-warning mb-3"></i>
                    <h2 class="fw-bold mb-2 text-dark">Keranjang Belanja</h2>
                    <p class="text-muted mb-0">Kelola produk yang ingin Anda beli</p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- 📦 Daftar Keranjang -->
                <div class="card shadow-lg rounded-4 p-4 border-0">
                    <h4 class="fw-bold mb-4">
                        <i class="fa-solid fa-cart-shopping me-2 text-dark"></i> Produk di Keranjang
                    </h4>

                    @if ($cartItems->count() > 0)
                        <!-- 💻 Tabel Desktop -->
                        <div class="table-responsive d-none d-md-block mb-4">
                            <table class="table align-middle">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th>Produk</th>
                                        <th class="text-center">Harga</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $item)
                                        @php
                                            $gambarPath = public_path($item->produk->gambar ?? '');
                                            $gambarUrl = ($item->produk && $item->produk->gambar && file_exists($gambarPath))
                                                ? asset($item->produk->gambar)
                                                : asset('img/no-image.jpg');
                                        @endphp

                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $gambarUrl }}" alt="{{ $item->produk->nama_produk ?? 'Produk' }}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                    <div>
                                                        <strong class="text-dark">{{ $item->produk->nama_produk ?? 'Produk tidak tersedia' }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $item->produk->nama_kategori ?? '-' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-warning text-dark">Rp {{ number_format(optional($item->produk)->harga ?? 0, 0, ',', '.') }}</span>
                                            </td>
                                            <td class="text-center">
                                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="input-group input-group-sm" style="max-width: 100px;">
                                                        <button type="button" class="btn btn-outline-dark" onclick="changeQty(this, -1)">−</button>
                                                        <input type="number" name="qty" value="{{ $item->qty }}" min="1" max="{{ $item->produk->stok ?? 1 }}" class="form-control text-center" onchange="this.form.submit()">
                                                        <button type="button" class="btn btn-outline-dark" onclick="changeQty(this, 1)">+</button>
                                                    </div>
                                                </form>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-success">Rp {{ number_format($item->qty * (optional($item->produk)->harga ?? 0), 0, ',', '.') }}</span>
                                            </td>
                                            <td class="text-center">
                                                <form action="{{ route('cart.remove', $item->id) }}" method="POST" onsubmit="return confirm('Hapus produk dari keranjang?')" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                                        <i class="fa-solid fa-trash me-1"></i> Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- 📱 Kartu Mobile -->
                        <div class="d-block d-md-none">
                            @foreach ($cartItems as $item)
                                @php
                                    $gambarPath = public_path($item->produk->gambar ?? '');
                                    $gambarUrl = ($item->produk && $item->produk->gambar && file_exists($gambarPath))
                                        ? asset($item->produk->gambar)
                                        : asset('img/no-image.jpg');
                                @endphp

                                <div class="border rounded-4 p-3 mb-3 shadow-sm bg-light">
                                    <div class="d-flex align-items-start mb-2">
                                        <img src="{{ $gambarUrl }}" alt="{{ $item->produk->nama_produk ?? 'Produk' }}" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-1">{{ $item->produk->nama_produk ?? 'Produk tidak tersedia' }}</h6>
                                            <p class="text-warning fw-bold mb-1">Rp {{ number_format(optional($item->produk)->harga ?? 0, 0, ',', '.') }}</p>
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center gap-2">
                                                @csrf
                                                @method('PUT')
                                                <button type="button" class="btn btn-outline-dark btn-sm" onclick="changeQty(this, -1)">−</button>
                                                <input type="number" name="qty" value="{{ $item->qty }}" min="1" max="{{ $item->produk->stok ?? 1 }}" class="form-control form-control-sm text-center" style="max-width: 60px;" onchange="this.form.submit()">
                                                <button type="button" class="btn btn-outline-dark btn-sm" onclick="changeQty(this, 1)">+</button>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <span class="badge bg-success">Total: Rp {{ number_format($item->qty * (optional($item->produk)->harga ?? 0), 0, ',', '.') }}</span>
                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST" onsubmit="return confirm('Hapus produk dari keranjang?')" class="ms-auto">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm rounded-pill">
                                                <i class="fa-solid fa-trash me-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Total & Checkout -->
                        @php
                            $totalHarga = $cartItems->sum(fn($item) => $item->qty * (optional($item->produk)->harga ?? 0));
                        @endphp

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4 pt-3 border-top">
                            <h5 class="fw-bold mb-0">
                                Total: <span class="text-warning">Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                            </h5>
                            <a href="{{ route('checkout.index') }}" class="btn btn-warning rounded-pill px-4 fw-semibold">
                                <i class="fa-solid fa-cash-register me-2"></i> Checkout
                            </a>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fa-solid fa-cart-shopping fa-3x text-warning mb-3"></i>
                            <h5 class="text-muted mb-2">Keranjang Kosong</h5>
                            <p class="text-muted mb-4">Anda belum menambahkan produk apapun ke keranjang.</p>
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
function changeQty(button, delta) {
    const input = button.parentElement.querySelector('input[type="number"]');
    const newValue = parseInt(input.value) + delta;
    if (newValue >= parseInt(input.min) && newValue <= parseInt(input.max)) {
        input.value = newValue;
        input.form.submit();
    }
}
</script>

@include('inc.footer')
