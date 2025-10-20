@include('admin.header')

<div class="container py-5">
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <h2 class="fw-bold mb-4 text-dark">🛒 Tambah Produk</h2>

      {{-- Pesan error validasi --}}
      @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>Terjadi kesalahan!</strong>
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
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-semibold">Nama Produk</label>
            <input type="text" name="nama_produk" class="form-control" placeholder="Contoh: Kopi Arabika Premium" required>
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Harga (Rp)</label>
            <input type="number" name="harga" class="form-control" placeholder="Masukkan harga" required>
          </div>

          <div class="col-md-12">
            <label class="form-label fw-semibold">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="4" placeholder="Tulis deskripsi produk..."></textarea>
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Stok</label>
            <input type="number" name="stok" class="form-control" value="0">
          </div>

          <div class="col-md-8">
            <label class="form-label fw-semibold">Kategori</label>
            <select name="id_kategori" class="form-select" required>
              <option value="">-- Pilih Kategori --</option>
              @foreach($kategori as $k)
                <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Gambar Produk</label>
            <input type="file" name="gambar" class="form-control">
            <small class="text-muted">Format: JPG, PNG, max 2MB</small>
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Tautan E-Commerce</label>
            <input type="url" name="link_shopee" class="form-control mb-2" placeholder="Link Shopee (opsional)">
            <input type="url" name="link_tiktok" class="form-control" placeholder="Link TikTok (opsional)">
          </div>
        </div>

        <div class="mt-4 d-flex justify-content-end gap-2">
          <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary px-4">Kembali</a>
          <button class="btn btn-dark px-4">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

@include('admin.footer')
