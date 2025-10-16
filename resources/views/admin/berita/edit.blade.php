@include('admin.header')

<div class="container py-5">
    <h2 class="fw-bold mb-4">Edit Berita</h2>

    <form action="{{ route('admin.berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Judul</label>
            <input type="text" name="judul" class="form-control" value="{{ $berita->judul }}" required>
        </div>

        <div class="mb-3">
            <label>Konten</label>
            <textarea name="konten" rows="5" class="form-control" required>{{ $berita->konten }}</textarea>
        </div>

        <div class="mb-3">
            <label>Gambar Saat Ini</label><br>
            @if($berita->gambar)
                <img src="{{ asset($berita->gambar) }}" style="max-width:200px" class="mb-2 rounded">
            @endif
            <input type="file" name="gambar_file" class="form-control mb-2">
            <input type="url" name="gambar_url" class="form-control" placeholder="atau tempel URL gambar baru">
        </div>

        <div class="mb-3">
            <label>Sumber</label>
            <input type="text" name="sumber" class="form-control" value="{{ $berita->sumber }}">
        </div>

        <div class="mb-3">
            <label>Tautan Sumber</label>
            <input type="url" name="tautan_sumber" class="form-control" value="{{ $berita->tautan_sumber }}">
        </div>

        <div class="mb-3">
            <label>Tanggal Berita</label>
            <input type="date" name="tanggal" class="form-control" value="{{ $berita->tanggal }}">
        </div>


        <button class="btn btn-dark">Update</button>
        <a href="{{ route('admin.berita.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>

@include('admin.footer')
