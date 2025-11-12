@include('inc.header')

<div class="detailorder-page" style="padding-top: 120px; background: url('{{ asset('img/bghero.svg') }}') center/cover no-repeat;">
    <div class="container py-5" style="max-width: 800px;">
        <div class="card shadow-lg rounded-4 border-0 p-4">

            <!-- 🧾 Header Detail -->
            <h3 class="fw-bold mb-3">
                <i class="fa-solid fa-receipt me-2 text-dark"></i> Detail Pesanan #{{ $order->id }}
            </h3>

            <!-- 📅 Informasi Pesanan -->
            <div class="mb-3">
                <p><strong>Tanggal Pesan:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                <p><strong>Tanggal Ambil:</strong> {{ \Carbon\Carbon::parse($order->tanggal_ambil)->format('d M Y') }}</p>
                <p><strong>Metode Pembayaran:</strong>
                    @if($order->metode_pembayaran === 'bank_transfer') 🏦 Bank Transfer
                    @elseif($order->metode_pembayaran === 'qris') 📱 QRIS
                    @else 💵 COD
                    @endif
                </p>

                <!-- Status Pesanan -->
                <p><strong>Status Pesanan:</strong>
                    @if($order->status == 'pending')
                        <span class="badge bg-warning text-dark">Pending</span>
                    @elseif($order->status == 'proses')
                        <span class="badge bg-primary">Diproses</span>
                    @elseif($order->status == 'selesai')
                        <span class="badge bg-success">Selesai</span>
                    @else
                        <span class="badge bg-danger">Batal</span>
                    @endif
                </p>

                <!-- Status Pembayaran -->
                <p><strong>Status Pembayaran:</strong>
                    @if($order->status_pembayaran == 'belum_bayar')
                        <span class="badge bg-secondary">Belum Bayar</span>
                    @elseif($order->status_pembayaran == 'menunggu_verifikasi')
                        <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                    @elseif($order->status_pembayaran == 'lunas')
                        <span class="badge bg-success">Lunas</span>
                    @else
                        <span class="badge bg-danger">Gagal</span>
                    @endif
                </p>
            </div>

            <!-- 🏦 Info Rekening (khusus Bank Transfer) -->
            @if($order->metode_pembayaran === 'bank_transfer')
            <div class="alert alert-info d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <strong>🏦 Transfer ke:</strong><br>
                    <span>Bank BCA - <strong>1234567890</strong> a.n. <strong>Batik Wistara</strong></span>
                </div>
                <button class="btn btn-sm btn-outline-dark" onclick="navigator.clipboard.writeText('1234567890')">
                    <i class="fa-solid fa-copy me-1"></i> Salin
                </button>
            </div>
            @endif

            <hr>

            <!-- 🛍️ Barang yang Dipesan -->
            <h5 class="fw-bold mb-3">🛍️ Barang yang Dipesan</h5>
            <div class="table-responsive mb-4">
                <table class="table align-middle">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                            @if($order->status == 'selesai')
                            <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @php
                                        $fileName = basename($item->produk->gambar ?? '');
                                        // Map old database paths to actual filenames
                                        $imageMap = [
                                            'batik-parang.jpg' => '1760930150_14.jpg',
                                            'batik-mega-mendung.jpg' => '1760930168_6.jpg',
                                            'batik-sekar-jagad.jpg' => '1760930223_2.jpg',
                                        ];
                                        $actualFileName = $imageMap[$fileName] ?? $fileName;
                                        $gambarPath = public_path('uploads/produk/'.$actualFileName);
                                        $gambarUrl = (file_exists($gambarPath) && $actualFileName)
                                                ? asset('uploads/produk/'.$actualFileName)
                                                : asset('img/logo.png');
                                    @endphp
                                    <img src="{{ $gambarUrl }}"
                                        alt="{{ $item->produk->nama_produk }}"
                                        style="width: 50px; height: 50px; object-fit: cover;"
                                        class="rounded shadow-sm border">
                                    <div>
                                        <span class="fw-semibold">{{ $item->produk->nama_produk }}</span><br>
                                        <small class="text-muted">#{{ $item->id_produk }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $item->qty }}</td>
                            <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            @if($order->status == 'selesai')
                            <td>
                                @php
                                    // Cek apakah user sudah pernah review produk ini
                                    $hasReviewed = \App\Models\Review::where('user_id', auth()->id())
                                        ->where('id_produk', $item->id_produk)
                                        ->exists();
                                @endphp
                                @if($hasReviewed)
                                    <span class="badge bg-success">✓ Sudah Direview</span>
                                @else
                                    <a href="{{ route('produk.show', $item->produk->slug) }}#review-form"
                                       class="btn btn-sm btn-warning">
                                        <i class="fa-solid fa-star me-1"></i> Tulis Review
                                    </a>
                                @endif
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- 💰 Total -->
            <div class="d-flex justify-content-between align-items-center p-3 bg-dark text-white rounded-3 shadow-sm">
                <strong>Total</strong>
                <strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
            </div>

            <!-- 📤 Upload Bukti Pembayaran (jika Bank Transfer & Belum Bayar) -->
            @if($order->metode_pembayaran === 'bank_transfer' && $order->status_pembayaran === 'belum_bayar' && $order->status == 'pending')
            <hr>
            <h5 class="fw-bold mb-3">📤 Upload Bukti Pembayaran</h5>
            <form action="{{ route('user.order.uploadBukti', $order->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <input type="file" name="bukti_pembayaran" accept="image/*" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-warning rounded-pill px-4">
                    <i class="fa-solid fa-upload me-2"></i> Upload Bukti
                </button>
            </form>
            @endif

            <!-- 🧾 Preview Bukti Pembayaran -->
            @if($order->bukti_pembayaran)
            <hr>
            <h5 class="fw-bold mb-2">🧾 Bukti Pembayaran</h5>
            <img src="{{ asset('uploads/bukti/'.$order->bukti_pembayaran) }}"
                alt="Bukti Pembayaran"
                class="img-fluid rounded shadow-sm mb-3"
                style="max-width: 350px;">
            @endif

            <!-- ❌ Batalkan Pesanan -->
            @if($order->status == 'pending')
            <div class="text-end mt-4">
                <form action="{{ route('user.order.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Yakin batalkan pesanan ini?')">
                    @csrf
                    <button type="submit" class="btn btn-danger rounded-pill px-4">
                        <i class="fa-solid fa-times me-2"></i> Batalkan Pesanan
                    </button>
                </form>
            </div>
            @endif

            <!-- ⬅️ Kembali -->
            <div class="mt-3">
                <a href="{{ url('/user/dashboard') }}" class="btn btn-outline-dark rounded-pill px-4">
                    <i class="fa-solid fa-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

@include('inc.footer')
