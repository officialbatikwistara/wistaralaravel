@include('inc.header')
<link rel="stylesheet" href="{{ asset('js/checkout.js') }}">
<section class="checkout-section position-relative"
    style="background: url('{{ asset('img/bghero.svg') }}') center/cover no-repeat;
                min-height: 100vh;
                margin-top: -80px;
                padding: 140px 0 60px;">
    <!-- Overlay -->
    <div class="position-absolute top-0 start-0 w-100 h-100"
        style="background: rgba(0,0,0,0.55); backdrop-filter: blur(3px); z-index:1;"></div>

    <div class="container position-relative" style="z-index:2; max-width:920px;">
        <h2 class="fw-bold mb-4 text-center text-gold display-6">Checkout</h2>

        <form action="{{ route('checkout.process') }}" method="POST" class="bg-white p-4 rounded-4 shadow-lg">
            @csrf

            <!-- 🧍 Informasi Pemesan -->
            <div class="mb-4 pb-3 border-bottom">
                <h5 class="fw-bold mb-3">Informasi Pemesan</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nama" class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="nama" id="nama" value="{{ auth()->user()->name }}"
                            class="form-control rounded-pill" required>
                    </div>
                    <div class="col-md-6">
                        <label for="telepon" class="form-label fw-semibold">Nomor Telepon</label>
                        <input type="text" name="telepon" id="telepon" value="{{ auth()->user()->phone ?? '' }}"
                            class="form-control rounded-pill" required>
                    </div>
                    <div class="col-md-12">
                        <label for="tipe_order" class="form-label fw-semibold">Tipe Pengambilan</label>
                        <select name="tipe_order" id="tipe_order" class="form-select rounded-pill" required>
                            <option value="">Pilih tipe pengambilan</option>
                            <option value="ambil">Ambil di Toko</option>
                            <option value="kirim">Kirim ke Alamat</option>
                        </select>
                    </div>
                    <div class="col-md-12" id="alamatField" style="display:none;">
                        <label for="alamat" class="form-label fw-semibold">Alamat Pengiriman</label>
                        <textarea name="alamat" id="alamat" class="form-control rounded" rows="3"></textarea>
                    </div>
                    <div class="col-md-6" id="tanggalField" style="display:none;">
                        <label for="tanggal_ambil" class="form-label fw-semibold">Tanggal Pengambilan</label>
                        <input type="date" name="tanggal_ambil" id="tanggal_ambil" class="form-control rounded-pill">
                    </div>
                    <div class="col-md-6">
                        <label for="metode_pembayaran" class="form-label fw-semibold">Metode Pembayaran</label>
                        <select name="metode_pembayaran" id="metode_pembayaran" class="form-select rounded-pill"
                            required>
                            <option value="">Pilih metode pembayaran</option>
                            <option value="bank_transfer">🏦 Transfer Bank</option>
                            <option value="qris">📱 QRIS</option>
                            <option value="cod">💵 Bayar di Tempat (COD)</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label for="catatan" class="form-label fw-semibold">Catatan (Opsional)</label>
                        <textarea name="catatan" id="catatan" class="form-control rounded" rows="2"
                            placeholder="Contoh: diambil jam 2 siang..."></textarea>
                    </div>
                </div>
            </div>

            <!-- 🛍️ Barang yang Dipesan -->
            <div class="mb-4 pb-3 border-bottom">
                <h5 class="fw-bold mb-3">Barang yang Dipesan</h5>
                <div class="table-responsive">
                    <table class="table table-borderless align-middle">
                        <thead class="bg-dark text-white rounded-top">
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach ($cartItems as $item)
                                @php
                                    $subtotal = $item->qty * $item->produk->harga;
                                    $total += $subtotal;
                                @endphp
                                <tr class="border-bottom">
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            @php
                                                $fileName = basename($item->produk->gambar ?? '');
                                                $gambarPath = public_path('uploads/produk/' . $fileName);
                                                $gambarUrl =
                                                    file_exists($gambarPath) && $fileName
                                                        ? asset('uploads/produk/' . $fileName)
                                                        : asset('img/logo.png');
                                            @endphp
                                            <img src="{{ $gambarUrl }}" alt="{{ $item->produk->nama_produk }}"
                                                class="rounded shadow-sm border"
                                                style="width: 60px; height: 60px; object-fit: cover;">
                                            <div>
                                                <span
                                                    class="fw-semibold d-block">{{ $item->produk->nama_produk }}</span>
                                                <small
                                                    class="text-muted">{{ $item->produk->kategori->nama_kategori ?? 'Tanpa Kategori' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $item->qty }}</td>
                                    <td class="text-end fw-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 🎫 Kode Kupon -->
            <div class="mb-4 pb-3 border-bottom">
                <h5 class="fw-bold mb-3" style="color: #1a2247;">Kode Kupon (Opsional)</h5>
                <div class="row g-3">
                    <div class="col-md-8">
                        <input type="text" name="coupon_code" id="coupon_code" class="form-control rounded-pill"
                            placeholder="Masukkan kode kupon" style="border-color: #cda349;">
                    </div>
                    <div class="col-md-4">
                        <button type="button" id="apply_coupon" class="btn rounded-pill w-100"
                            style="background-color: #cda349; border-color: #cda349; color: white;">
                            Terapkan Kupon
                        </button>
                    </div>
                </div>
                <div id="coupon_message" class="mt-2"></div>
            </div>

            <!-- 💰 Total -->
            <div
                class="d-flex justify-content-between align-items-center p-3 bg-dark text-white rounded-3 shadow-sm mb-4">
                <span class="fw-bold fs-5">Total</span>
                <span class="fw-bold text-gold fs-5" id="total_display">Rp
                    {{ number_format($total, 0, ',', '.') }}</span>
                <input type="hidden" name="original_total" value="{{ $total }}">
                <input type="hidden" name="final_total" value="{{ $total }}">
                <input type="hidden" name="discount_amount" value="0">
                <input type="hidden" name="coupon_id" value="">
            </div>

            <!-- 🏪 Alamat Toko -->
            <div class="mb-4" id="alamatToko">
                <h5 class="fw-bold mb-2">📍 Alamat Toko</h5>
                <div class="p-3 bg-light rounded-3 border">
                    <p class="mb-2 fw-semibold">
                        Jl. Tambak Medokan Ayu VI C No.56B, Surabaya, Jawa Timur 60295
                    </p>
                    <a href="https://maps.app.goo.gl/WqHPo5eNBDqHykhM8" target="_blank"
                        class="btn btn-outline-dark btn-sm rounded-pill">
                        <i class="fa-solid fa-map-location-dot me-1"></i> Lihat di Google Maps
                    </a>
                </div>
            </div>

            <!-- 💳 Informasi Pembayaran Dinamis -->
            <div class="mb-4 d-none" id="paymentInfo">
                <div class="p-3 bg-light rounded-3 border text-center" id="bankInfo" style="display:none;">
                    <p class="fw-semibold mb-1">🏦 Bank BCA - <strong>1234567890</strong><br>a.n. <strong>Batik
                            Wistara</strong></p>
                    <button type="button" class="btn btn-sm btn-outline-dark mt-2"
                        onclick="navigator.clipboard.writeText('1234567890')">
                        <i class="fa-solid fa-copy me-1"></i> Salin Nomor Rekening
                    </button>
                </div>
                <div class="p-3 bg-light rounded-3 border text-center" id="qrisInfo" style="display:none;">
                    <div class="bg-secondary text-white d-inline-flex align-items-center justify-content-center rounded"
                        style="width: 200px; height: 200px; font-size: 1.2rem; font-weight: bold;">
                        QRIS<br>Coming Soon
                    </div>
                    <p class="mt-2 mb-0"><small>QRIS payment akan segera tersedia</small></p>
                </div>
            </div>

            <!-- ✅ Tombol Submit -->
            <div class="text-end">
                @if (request()->routeIs('checkout') || request()->routeIs('checkout.direct'))
                    @php
                        $firstItem = $cartItems->first();
                    @endphp
                    @if (isset($firstItem->id_produk))
                        <input type="hidden" name="id_produk" value="{{ $firstItem->id_produk }}">
                    @endif
                @endif

                <button type="submit"
                    class="btn btn-warning btn-lg rounded-pill px-4 fw-semibold text-dark shadow-sm">
                    <i class="fa-solid fa-check me-2"></i> Buat Pesanan
                </button>
            </div>

        </form>
    </div>
</section>

<script src="{{ asset('js/checkout.js') }}"></script>

@include('inc.footer')
