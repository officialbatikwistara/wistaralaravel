@include('admin.header')

<div class="container py-5">
  <div class="card shadow-lg border-0 rounded-4 mx-auto" style="max-width: 720px; background-color: var(--light-gray, #f8f9fb);">
    <!-- Header -->
    <div class="card-header text-white text-center rounded-top-4 py-4" style="background-color: var(--dark-navy, #071739);">
      <h3 class="mb-0 fw-bold" style="letter-spacing: 0.5px;">Edit Berita</h3>
    </div>

    <!-- Body -->
    <div class="card-body px-5 py-4" style="background-color: #fdfdfd;">
      <form action="{{ route('admin.berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Judul -->
        <div class="mb-4">
          <label class="form-label fw-semibold text-dark-navy">Judul</label>
          <input
            type="text"
            name="judul"
            class="form-control border-0 shadow-sm rounded-3 p-3"
            value="{{ $berita->judul }}"
            required>
        </div>

        <!-- Konten -->
        <div class="mb-4">
          <label class="form-label fw-semibold text-dark-navy">Konten</label>
          <textarea
            name="konten"
            rows="6"
            class="form-control border-0 shadow-sm rounded-3 p-3"
            required>{{ $berita->konten }}</textarea>
        </div>

        <!-- Gambar -->
        <div class="mb-4">
          <label class="form-label fw-semibold text-dark-navy">Gambar Saat Ini</label><br>
          @if($berita->gambar)
            <img src="{{ asset($berita->gambar) }}" class="mb-3 rounded shadow-sm" style="max-width: 200px; border: 3px solid var(--blue-gray);">
          @else
            <p class="text-muted">Belum ada gambar</p>
          @endif
          <input type="file" name="gambar_file" class="form-control border-0 shadow-sm rounded-3 mb-2 p-2">
          <input type="url" name="gambar_url" class="form-control border-0 shadow-sm rounded-3 p-3" placeholder="Atau tempel URL gambar baru">
        </div>

        <!-- Sumber -->
        <div class="mb-4">
          <label class="form-label fw-semibold text-dark-navy">Sumber</label>
          <input
            type="text"
            name="sumber"
            class="form-control border-0 shadow-sm rounded-3 p-3"
            value="{{ $berita->sumber }}">
        </div>

        <!-- Tautan -->
        <div class="mb-4">
          <label class="form-label fw-semibold text-dark-navy">Tautan Sumber</label>
          <input
            type="url"
            name="tautan_sumber"
            class="form-control border-0 shadow-sm rounded-3 p-3"
            value="{{ $berita->tautan_sumber }}">
        </div>

        <!-- Tanggal -->
        <div class="mb-4">
          <label class="form-label fw-semibold text-dark-navy">Tanggal Berita</label>
          <input
            type="date"
            name="tanggal"
            class="form-control border-0 shadow-sm rounded-3 p-3"
            value="{{ $berita->tanggal }}">
        </div>

        <!-- Tombol -->
        <div class="d-flex justify-content-end gap-3 mt-4">
          <a href="{{ route('admin.berita.index') }}" class="btn btn-custom-secondary px-4 py-2 rounded-3 fw-semibold">
            Kembali
          </a>
          <button class="btn btn-custom-primary px-4 py-2 rounded-3 fw-semibold">
            Update
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@include('admin.footer')

<style>
  :root {
    --dark-navy: #071739;
    --blue-gray: #4B6382;
    --light-gray: #f5f7fa;
    --gold: #d4af37;
  }

  .text-dark-navy { color: var(--dark-navy); }

  .card {
    animation: fadeIn 0.5s ease;
  }

  textarea.form-control {
    resize: vertical;
    min-height: 140px;
  }

  input:focus, textarea:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(7, 23, 57, 0.2);
  }

  /* Tombol utama */
  .btn-custom-primary {
    background-color: var(--dark-navy);
    color: #fff;
    border: none;
    transition: 0.3s ease;
  }

  .btn-custom-primary:hover {
    opacity: 0.9;
    transform: translateY(-1px);
  }

  .btn-custom-primary:active {
    background-color: var(--gold);
    color: #000;
    transform: scale(0.98);
  }

  /* Tombol sekunder */
  .btn-custom-secondary {
    background-color: var(--blue-gray);
    color: #fff;
    border: none;
    transition: 0.3s ease;
  }

  .btn-custom-secondary:hover {
    opacity: 0.9;
    transform: translateY(-1px);
  }

  .btn-custom-secondary:active {
    background-color: var(--gold);
    color: #000;
    transform: scale(0.98);
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
  }
</style>
