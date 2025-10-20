@include('admin.header')

<div class="container py-5">
  <div class="card shadow-lg border-0 rounded-4 mx-auto" style="max-width: 600px;">
    <div class="card-header bg-dark text-white text-center rounded-top-4 py-3">
      <h3 class="mb-0">➕ Tambah Kategori Produk</h3>
    </div>
    <div class="card-body bg-light">
      <form action="{{ route('admin.kategori.store') }}" method="POST">
        @csrf

        <div class="mb-3">
          <label class="form-label fw-semibold">Nama Kategori</label>
          <input
            type="text"
            name="nama_kategori"
            class="form-control border-0 shadow-sm"
            placeholder="Masukkan nama kategori..."
            required>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
          <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary px-4">
            ← Kembali
          </a>
          <button class="btn btn-dark px-4">
            Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@include('admin.footer')
