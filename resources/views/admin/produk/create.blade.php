@include('admin.header')

<div class="container py-4">
  <h2 class="mb-4">Tambah Produk</h2>
  <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
      <label>Nama Produk</label>
      <input type="text" name="nama_produk" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Deskripsi</label>
      <textarea name="deskripsi" class="form-control" rows="4"></textarea>
    </div>

    <div class="mb-3">
      <label>Harga</label>
      <input type="number" name="harga" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Stok</label>
      <input type="number" name="stok" class="form-control" value="0">
    </div>

    <div class="mb-3">
      <label>Kategori</label>
      <select name="id_kategori" class="form-select" required>
        @foreach($kategori as $k)
          <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label>Gambar Produk</label>
      <input type="file" name="gambar" class="form-control">
    </div>

    <div class="mb-3">
      <label>Link Shopee</label>
      <input type="url" name="link_shopee" class="form-control">
    </div>

    <div class="mb-3">
      <label>Link TikTok</label>
      <input type="url" name="link_tiktok" class="form-control">
    </div>

    <button class="btn btn-dark">Simpan</button>
    <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary">Kembali</a>
  </form>
</div>
@if ($errors->any())
  <div class="alert alert-danger">
      <ul>
          @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
          @endforeach
      </ul>
  </div>
@endif

@include('admin.footer')
