@include('inc.header')

<section class="checkout-section position-relative"
         style="background: url('{{ asset('img/bghero.svg') }}') center/cover no-repeat;
                min-height: 100vh;
                margin-top: -80px;
                padding: 140px 0 60px;">

  <!-- Overlay -->
  <div class="position-absolute top-0 start-0 w-100 h-100"
       style="background: rgba(0, 0, 0, 0.55); backdrop-filter: blur(3px); z-index: 1;"></div>

  <div class="container position-relative" style="z-index: 2; max-width: 920px;">
    <h2 class="fw-bold mb-4 text-center text-gold display-6">Checkout</h2>

    <form action="{{ route('checkout.process') }}" method="POST" class="bg-white p-4 rounded-4 shadow-lg">
      @csrf

      <!-- 🧍 Informasi Pemesan -->
      <div class="mb-4 pb-3 border-bottom">
        <h5 class="fw-bold mb-3">Informasi Pemesan</h5>
        <div class="row g-3">
          <div class="col-md-6">
            <label for="nama" class="form-label fw-semibold">Nama Lengkap</label>
            <input type="text" name="nama" id="nama"
                   value="{{ auth()->user()->name }}"
                   class="form-control rounded-pill" required readonly>
          </div>
          <div class="col-md-6">
            <label for="telepon" class="form-label fw-semibold">Nomor Telepon</label>
            <input type="text" name="telepon" id="telepon"
                   value="{{ auth()->user()->phone ?? '' }}"
                   class="form-control rounded-pill"
                   required {{ auth()->user()->phone ? 'readonly' : '' }}>
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
              @foreach($cartItems as $item)
                @php
                  $subtotal = $item->qty * $item->produk->harga;
                  $total += $subtotal;
                @endphp
                <tr class="border-bottom">
                  <td>
                    <div class="d-flex align-items-center gap-3">
                      <img src="{{ asset($item->produk->gambar) }}" 
                           alt="{{ $item->produk->nama_produk }}" 
                           class="rounded shadow-sm border"
                           style="width: 60px; height: 60px; object-fit: cover;">
                      <div>
                        <span class="fw-semibold d-block">{{ $item->produk->nama_produk }}</span>
                        <small class="text-muted">{{ $item->produk->kategori->nama_kategori ?? 'Tanpa Kategori' }}</small>
                      </div>
                    </div>
                  </td>
                  <td class="text-center">{{ $item->qty }}</td>
                  <td class="text-end fw-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <!-- 🚚 Metode Pengambilan -->
      <div class="mb-4 pb-3 border-bottom">
        <h5 class="fw-bold mb-3">Metode Pengambilan</h5>

        <div class="form-check mb-2">
          <input class="form-check-input" type="radio" name="tipe_order" value="ambil" id="tipeAmbil" checked>
          <label class="form-check-label fw-semibold" for="tipeAmbil">
            🏬 Ambil di Toko
          </label>
        </div>

        <div class="form-check">
          <input class="form-check-input" type="radio" name="tipe_order" value="kirim" id="tipeKirim" disabled>
          <label class="form-check-label text-muted" for="tipeKirim">
            🚚 Kirim ke Alamat (Coming Soon)
          </label>
        </div>
      </div>

      <!-- 🏠 Alamat Pengiriman -->
      <div class="mb-4 d-none" id="alamatSection">
        <h5 class="fw-bold mb-3">Alamat Pengiriman</h5>
        <textarea name="alamat" rows="3" class="form-control rounded-3"
                  placeholder="Masukkan alamat lengkap..."></textarea>
      </div>

      <!-- 🏪 Alamat Toko -->
      <div class="mb-4" id="alamatToko">
        <h5 class="fw-bold mb-2">📍 Alamat Toko</h5>
        <div class="p-3 bg-light rounded-3 border">
          <p class="mb-2 fw-semibold">
            Jl. Tambak Medokan Ayu VI C No.56B, Surabaya, Jawa Timur 60295
          </p>
          <a href="https://maps.app.goo.gl/WqHPo5eNBDqHykhM8" target="_blank" class="btn btn-outline-dark btn-sm rounded-pill">
            <i class="fa-solid fa-map-location-dot me-1"></i> Lihat di Google Maps
          </a>
        </div>
      </div>

      <!-- 📅 Tanggal Ambil -->
      <div class="mb-4" id="tanggalAmbilSection">
        <h5 class="fw-bold mb-3">🗓️ Pilih Tanggal Ambil</h5>
        <input type="date" name="tanggal_ambil" id="tanggalAmbil"
               class="form-control rounded-pill" required>
      </div>

      <!-- 📝 Catatan -->
      <div class="mb-4">
        <h5 class="fw-bold mb-3">Catatan (Opsional)</h5>
        <textarea name="catatan" rows="2" class="form-control rounded-3"
                  placeholder="Contoh: diambil jam 2 siang..."></textarea>
      </div>

      <!-- 💰 Total -->
      <div class="d-flex justify-content-between align-items-center p-3 bg-dark text-white rounded-3 shadow-sm mb-4">
        <span class="fw-bold fs-5">Total</span>
        <span class="fw-bold text-gold fs-5">Rp {{ number_format($total, 0, ',', '.') }}</span>
      </div>

      <!-- ✅ Tombol Submit -->
      <div class="text-end">
        <button type="submit" class="btn btn-warning btn-lg rounded-pill px-4 fw-semibold text-dark shadow-sm">
          <i class="fa-solid fa-check me-2"></i> Buat Pesanan
        </button>
      </div>
    </form>
  </div>
</section>

<script>
  const ambil = document.getElementById('tipeAmbil');
  const kirim = document.getElementById('tipeKirim');
  const alamatSection = document.getElementById('alamatSection');
  const alamatToko = document.getElementById('alamatToko');
  const tanggalAmbilSection = document.getElementById('tanggalAmbilSection');

  // Minimal tanggal hari ini
  const today = new Date().toISOString().split('T')[0];
  document.getElementById('tanggalAmbil').setAttribute('min', today);

  ambil.addEventListener('change', () => {
    alamatSection.classList.add('d-none');
    alamatToko.classList.remove('d-none');
    tanggalAmbilSection.classList.remove('d-none');
  });

  kirim.addEventListener('change', () => {
    alamatSection.classList.remove('d-none');
    alamatToko.classList.add('d-none');
    tanggalAmbilSection.classList.add('d-none');
  });
</script>

@include('inc.footer')
