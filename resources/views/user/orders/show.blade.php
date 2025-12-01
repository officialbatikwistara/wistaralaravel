@include('inc.header')

<div class="detailorder-page" style="padding-top: 120px; background: url('{{ asset('img/bghero.svg') }}') center/cover no-repeat;">
    <div class="container py-5" style="max-width: 800px;">
        <div class="card shadow-lg rounded-4 border-0 p-4">

            <!-- 🧾 Header Detail -->
            <h3 class="fw-bold mb-3">
                <i class="fa-solid fa-receipt me-2 text-dark"></i> Detail Pesanan #{{ $order->order_code }}
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
                                        <small class="text-muted">#{{ $item->produk->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $item->qty }}</td>
                            <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            @if($order->status == 'selesai')
                            <td>
                                @php
                                    $hasReviewed = \App\Models\Review::where('user_id', auth()->id())
                                        ->where('id_produk', $item->produk->id_produk)
                                        ->where('order_id', $order->id)
                                        ->exists();
                                @endphp

                                @if($hasReviewed)
                                    <span class="badge bg-success">✓ Sudah Direview</span>
                                @else
                                    <button class="btn btn-sm btn-warning"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalReview{{ $item->id }}">
                                        <i class="fa-solid fa-star me-1"></i> Tulis Review
                                    </button>
                                @endif
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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

<!-- 💰 Total -->
<div class="d-flex justify-content-between align-items-center p-3 bg-dark text-white rounded-3 shadow-sm">
    <strong>Total</strong>
    <strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
</div>

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

<!-- 📄 Lihat Invoice -->
<a href="{{ route('user.orders.invoice', $order->id) }}"
   class="btn btn-outline-dark rounded-pill px-4 mt-3">
   <i class="fa-solid fa-file-pdf me-2"></i> Lihat Invoice
</a>

<!-- ⬅️ Kembali -->
<div class="mt-3">
    <a href="{{ url('/user/dashboard') }}" class="btn btn-outline-dark rounded-pill px-4">
        <i class="fa-solid fa-arrow-left me-2"></i> Kembali
    </a>
</div>

        </div>
    </div>
</div>

<!-- ========== MODAL REVIEW PER PRODUK ==========  -->
@foreach($order->items as $item)

    @php
    $hasReviewed = \App\Models\Review::where('user_id', auth()->id())
        ->where('id_produk', $item->produk->id)
        ->where('order_id', $order->id)
        ->exists();
    @endphp

    @if(!$hasReviewed)
        <div class="modal fade" id="modalReview{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form class="modal-content"
                      action="{{ route('review.store') }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <input type="hidden" name="id_produk" value="{{ $item->id_produk }}">

                    <div class="modal-header">
                        <h5 class="modal-title">
                            Tulis Review • {{ $item->produk->nama_produk }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        {{-- Rating --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Rating</label>
                            <select name="rating" class="form-select" required>
                                <option value="">Pilih rating</option>
                                <option value="5">⭐⭐⭐⭐⭐ Sangat Puas</option>
                                <option value="4">⭐⭐⭐⭐ Puas</option>
                                <option value="3">⭐⭐⭐ Cukup</option>
                                <option value="2">⭐⭐ Kurang</option>
                                <option value="1">⭐ Tidak Puas</option>
                            </select>
                        </div>

                        {{-- Comment --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Review</label>
                            <textarea name="comment" class="form-control" rows="4" required></textarea>
                        </div>

                        {{-- Foto (opsional) --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Foto (opsional)</label>
                            <input type="file" name="photos[]" class="form-control" multiple accept="image/*">
                            <small class="text-muted">Bisa upload beberapa foto produk.</small>
                        </div>

                        {{-- Video (opsional) --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Video (opsional)</label>
                            <input type="file" name="video" class="form-control" accept="video/*">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-warning">
                            Kirim Review
                        </button>
                    </div>

                </form>
            </div>
        </div>
    @endif

@endforeach



@include('inc.footer')
