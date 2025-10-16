@include('admin.header')

<div class="container py-5">
    <h2 class="fw-bold mb-4">Tambah Berita</h2>
    <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Judul</label>
            <input type="text" name="judul" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Konten</label>
            <textarea name="konten" rows="5" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label>Gambar (pilih salah satu)</label>
            <input type="file" name="gambar_file" class="form-control mb-2">
            <input type="url" name="gambar_url" class="form-control" placeholder="atau tempel URL gambar">
        </div>

        <div class="mb-3">
            <label>Sumber</label>
            <input type="text" name="sumber" class="form-control" placeholder="Nama sumber (opsional)">
        </div>

        <div class="mb-3">
            <label>Tautan Sumber</label>
            <input type="url" name="tautan_sumber" class="form-control" placeholder="https://... (opsional)">
        </div>

        <div class="mb-3">
            <label>Tanggal Berita</label>
            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}">
            <small class="text-muted">Tanggal rilis berita eksternal atau internal.</small>
        </div>

        <button class="btn btn-dark">Simpan</button>
        <a href="{{ route('admin.berita.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>

@include('admin.footer')
