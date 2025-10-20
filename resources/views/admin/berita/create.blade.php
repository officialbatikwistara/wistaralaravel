@include('admin.header')

<div class="container py-5">
  <div class="card shadow-lg border-0 rounded-4 mx-auto" style="max-width: 700px;">
    <div class="card-header bg-dark text-white text-center rounded-top-4 py-3">
      <h3 class="mb-0">📰 Tambah Berita Baru</h3>
    </div>

    <div class="card-body bg-light">
      <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Judul -->
        <div class="mb-3">
          <label class="form-label fw-semibold">Judul Berita</label>
          <input
            type="text"
            name="judul"
            class="form-control border-0 shadow-sm"
            placeholder="Masukkan judul berita..."
            required>
        </div>

        <!-- Konten -->
        <div class="mb-3">
          <label class="form-label fw-semibold">Konten</label>
          <textarea
            name="konten"
            rows="6"
            class="form-control border-0 shadow-sm"
            placeholder="Tulis isi berita di sini..."
            required></textarea>
        </div>

        <!-- Gambar -->
        <div class="mb-3">
          <label class="form-label fw-semibold">Gambar (pilih salah satu)</label>
          <input type="file" name="gambar_file" class="form-control mb-2 border-0 shadow-sm">
          <input type="url" name="gambar_url" class="form-control border-0 shadow-sm" placeholder="Atau tempel URL gambar...">
        </div>

        <!-- Sumber -->
        <div class="mb-3">
          <label class="form-label fw-semibold">Sumber</label>
          <input
            type="text"
            name="sumber"
            class="form-control border-0 shadow-sm"
            placeholder="Nama sumber (opsional)">
        </div>

        <!-- Tautan -->
        <div class="mb-3">
          <label class="form-label fw-semibold">Tautan Sumber</label>
          <input
            type="url"
            name="tautan_sumber"
            class="form-control border-0 shadow-sm"
            placeholder="https://... (opsional)">
        </div>

        <!-- Tanggal -->
        <div class="mb-3">
          <label class="form-label fw-semibold">Tanggal Berita</label>
          <input
            type="date"
            name="tanggal"
            class="form-control border-0 shadow-sm"
            value="{{ date('Y-m-d') }}">
          <small class="text-muted">Tanggal rilis berita internal atau eksternal.</small>
        </div>

        <!-- Tombol -->
        <div class="d-flex justify-content-end gap-2 mt-4">
          <a href="{{ route('admin.berita.index') }}" class="btn btn-secondary px-4">
            ← Kembali
          </a>
          <button class="btn btn-dark px-4">
            Simpan Berita
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@include('admin.footer')

<style>
  .card {
    animation: fadeIn 0.5s ease;
  }

  textarea.form-control {
    resize: vertical;
    min-height: 140px;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
  }
</style>
