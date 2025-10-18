@include('admin.header')

<div class="container py-4">
  <h2 class="mb-4">✏️ Edit Produk</h2>

  <form action="{{ route('admin.produk.update', $produk->id_produk) }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- Nama Produk -->
    <div class="mb-3">
      <label>Nama Produk</label>
      <input type="text" name="nama_produk" class="form-control" value="{{ $produk->nama_produk }}" required>
    </div>

    <!-- Deskripsi -->
    <div class="mb-3">
      <label>Deskripsi</label>
      <textarea name="deskripsi" class="form-control" rows="4">{{ $produk->deskripsi }}</textarea>
    </div>

    <!-- Harga -->
    <div class="mb-3">
      <label>Harga</label>
      <input type="number" name="harga" class="form-control" value="{{ $produk->harga }}" required>
    </div>

    <!-- Stok -->
    <div class="mb-3">
      <label>Stok</label>
      <input type="number" name="stok" class="form-control" value="{{ $produk->stok }}">
    </div>

    <!-- Kategori -->
    <div class="mb-3">
      <label>Kategori</label>
      <select name="id_kategori" class="form-select" required>
        @foreach($kategori as $k)
          <option value="{{ $k->id_kategori }}" 
            {{ $produk->id_kategori == $k->id_kategori ? 'selected' : '' }}>
            {{ $k->nama_kategori }}
          </option>
        @endforeach
      </select>
    </div>

    <!-- Gambar -->
    <div class="mb-3">
      <label>Gambar Produk</label>
      @if($produk->gambar)
        <div class="mb-2">
          <img src="{{ asset($produk->gambar) }}" alt="Gambar produk" class="img-thumbnail" style="max-height: 150px;">
        </div>
      @endif
      <input type="file" name="gambar" class="form-control">
      <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>
    </div>

    <!-- Link Shopee -->
    <div class="mb-3">
      <label>Link Shopee</label>
      <input type="url" name="link_shopee" class="form-control" value="{{ $produk->link_shopee }}">
    </div>

    <!-- Link TikTok -->
    <div class="mb-3">
      <label>Link TikTok</label>
      <input type="url" name="link_tiktok" class="form-control" value="{{ $produk->link_tiktok }}">
    </div>

    <div class="mt-4 d-flex gap-2">
      <button type="submit" class="btn btn-dark">💾 Simpan Perubahan</button>
      <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
  </form>
</div>

@include('admin.footer')
