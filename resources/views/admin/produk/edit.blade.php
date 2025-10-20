@include('admin.header')

<div class="container py-5">
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <h2 class="fw-bold mb-4 text-dark">✏️ Edit Produk</h2>

      {{-- Alert untuk error validasi --}}
      @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>Terjadi kesalahan:</strong>
          <ul class="mb-0 mt-1">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      <form action="{{ route('admin.produk.update', $produk->id_produk) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-semibold">Nama Produk</label>
            <input type="text" name="nama_produk" class="form-control" value="{{ $produk->nama_produk }}" required>
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Harga (Rp)</label>
            <input type="number" name="harga" class="form-control" value="{{ $produk->harga }}" required>
          </div>

          <div class="col-md-12">
            <label class="form-label fw-semibold">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="4">{{ $produk->deskripsi }}</textarea>
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">Stok</label>
            <input type="number" name="stok" class="form-control" value="{{ $produk->stok }}">
          </div>

          <div class="col-md-8">
            <label class="form-label fw-semibold">Kategori</label>
            <select name="id_kategori" class="form-select" required>
              <option value="">-- Pilih Kategori --</option>
              @foreach($kategori as $k)
                <option value="{{ $k->id_kategori }}" {{ $produk->id_kategori == $k->id_kategori ? 'selected' : '' }}>
                  {{ $k->nama_kategori }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Gambar Produk</label>
            @if($produk->gambar)
              <div class="mb-2">
                <img src="{{ asset($produk->gambar) }}" alt="Gambar produk" class="rounded shadow-sm" style="max-width: 150px;">
              </div>
            @endif
            <input type="file" name="gambar" class="form-control">
            <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Tautan E-Commerce</label>
            <input type="url" name="link_shopee" class="form-control mb-2" value="{{ $produk->link_shopee }}" placeholder="Link Shopee (opsional)">
            <input type="url" name="link_tiktok" class="form-control" value="{{ $produk->link_tiktok }}" placeholder="Link TikTok (opsional)">
          </div>
        </div>

        <div class="mt-4 d-flex justify-content-end gap-2">
          <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary px-4">Kembali</a>
          <button type="submit" class="btn btn-dark px-4">💾 Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

@include('admin.footer')
