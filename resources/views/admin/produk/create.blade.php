@include('admin.header')

<div class="container py-5">
  <div class="card shadow-lg border-0 rounded-4 mx-auto" style="max-width: 900px;">
    {{-- Header biru tua --}}
    <div class="card-header text-white text-center rounded-top-4 py-4" style="background-color: #081738;">
      <h3 class="mb-0 fw-bold"> Tambah Produk Baru</h3>
    </div>

    {{-- Body --}}
    <div class="card-body bg-white p-4">
      {{-- Pesan Error --}}
      @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3" role="alert">
          <strong>Terjadi kesalahan:</strong>
          <ul class="mb-0 mt-1">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      {{-- Form Tambah Produk --}}
      <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
          {{-- Nama Produk --}}
          <div class="col-md-6">
            <label class="form-label fw-semibold text-dark">Nama Produk</label>
            <input type="text" name="nama_produk" class="form-control border-0 shadow-sm"
                   placeholder="Contoh: Batik Wistara Premium" required>
          </div>

          {{-- Harga --}}
          <div class="col-md-6">
            <label class="form-label fw-semibold text-dark">Harga (Rp)</label>
            <input type="number" name="harga" class="form-control border-0 shadow-sm"
                   placeholder="Masukkan harga" required>
          </div>

          {{-- Deskripsi --}}
          <div class="col-md-12">
            <label class="form-label fw-semibold text-dark">Deskripsi</label>
            <textarea name="deskripsi" class="form-control border-0 shadow-sm" rows="4"
                      placeholder="Tulis deskripsi produk di sini..."></textarea>
          </div>

          {{-- Stok --}}
          <div class="col-md-4">
            <label class="form-label fw-semibold text-dark">Stok</label>
            <input type="number" name="stok" class="form-control border-0 shadow-sm" value="0" placeholder="Jumlah stok">
          </div>

          {{-- Kategori --}}
          <div class="col-md-8">
            <label class="form-label fw-semibold text-dark">Kategori</label>
            <select name="id_kategori" class="form-select border-0 shadow-sm" required>
              <option value="">-- Pilih Kategori --</option>
              @foreach($kategori as $k)
                <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
              @endforeach
            </select>
          </div>

          {{-- Gambar Produk --}}
          <div class="col-md-6">
            <label class="form-label fw-semibold text-dark">Gambar Produk</label>
            <input type="file" name="gambar" class="form-control border-0 shadow-sm">
            <small class="text-muted">Format: JPG, PNG | Maks: 2MB</small>
          </div>

          {{-- Link e-commerce --}}
          <div class="col-md-6">
            <label class="form-label fw-semibold text-dark">Tautan E-Commerce</label>
            <input type="url" name="link_shopee" class="form-control border-0 shadow-sm mb-2"
                   placeholder="Link Shopee (opsional)">
            <input type="url" name="link_tiktok" class="form-control border-0 shadow-sm"
                   placeholder="Link TikTok (opsional)">
          </div>
        </div>

        {{-- Tombol --}}
        <div class="d-flex justify-content-end gap-2 mt-5">
          <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary px-4 py-2 rounded-pill shadow-sm">
            Kembali
          </a>
          <button type="submit" class="btn btn-dark px-4 py-2 rounded-pill shadow-sm">
            Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@include('admin.footer')

{{-- 🌸 Style Modern & Konsisten --}}
<style>
body {
  background-color: #f7f9fc;
  font-family: 'Poppins', sans-serif;
  color: #0b1841;
}

.card {
  border-radius: 24px !important;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08) !important;
}

.card-header {
  border-top-left-radius: 24px !important;
  border-top-right-radius: 24px !important;
  background-color: #081738 !important;
}

h3 {
  font-weight: 700;
  color: #ffffff;
}

/* Input & Select */
.form-control, .form-select {
  border-radius: 14px !important;
  padding: 12px 16px !important;
  border: 1px solid #e5e7eb !important;
  transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
  border-color: #1e3a8a !important;
  box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.15) !important;
}

/* Tombol */
.btn-dark {
  background-color: #081738 !important;
  border: none !important;
  font-weight: 600 !important;
  border-radius: 14px !important;
}

.btn-dark:hover {
  background-color: #132a73 !important;
}

.btn-secondary {
  background-color: #6b7280 !important;
  border: none !important;
  border-radius: 14px !important;
  font-weight: 600 !important;
}

.btn-secondary:hover {
  background-color: #4b5563 !important;
}
</style>
