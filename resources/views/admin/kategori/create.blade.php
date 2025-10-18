@include('admin.header')

<div class="container py-4">
  <h2 class="mb-4">➕ Tambah Kategori</h2>
  <form action="{{ route('admin.kategori.store') }}" method="POST">
    @csrf
    <div class="mb-3">
      <label>Nama Kategori</label>
      <input type="text" name="nama_kategori" class="form-control" required>
    </div>
    <button class="btn btn-dark">Simpan</button>
    <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary">Kembali</a>
  </form>
</div>

@include('admin.footer')
