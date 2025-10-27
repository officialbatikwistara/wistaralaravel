@include('admin.header')

<div class="container py-5">
  <div class="card shadow-lg border-0 rounded-4 mx-auto" style="max-width: 700px;">
    {{-- Header biru tua --}}
    <div class="card-header text-white text-center rounded-top-4 py-4" style="background-color: #081738;">
      <h3 class="mb-0 fw-bold">Tambah Kategori Produk</h3>
    </div>

    {{-- Body putih lembut --}}
    <div class="card-body bg-white p-4">
      <form action="{{ route('admin.kategori.store') }}" method="POST">
        @csrf

        {{-- Input Nama Kategori --}}
        <div class="mb-4">
          <label class="form-label fw-semibold text-dark" style="color: #0b1841;">Nama Kategori</label>
          <input
            type="text"
            name="nama_kategori"
            class="form-control shadow-sm border-0"
            placeholder="Masukkan nama kategori..."
            required>
        </div>

        {{-- Tombol Aksi --}}
        <div class="d-flex justify-content-end gap-2 mt-4">
          <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary px-4 py-2 rounded-pill shadow-sm">
            ← Kembali
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

{{-- 🌸 Style selaras dengan halaman Tambah Berita --}}
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

/* 🧾 Input */
.form-control {
  border-radius: 14px !important;
  padding: 12px 16px !important;
  border: 1px solid #e5e7eb !important;
  transition: all 0.3s ease;
}

.form-control:focus {
  border-color: #1e3a8a !important;
  box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.15) !important;
}

/* 🎨 Tombol */
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
