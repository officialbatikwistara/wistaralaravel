@include('admin.header')

<link rel="stylesheet" href="{{ asset('css/kategori/edit.css') }}">

<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-4 mx-auto" style="max-width: 700px;">
        {{-- Header biru tua elegan --}}
        <div class="card-header text-white text-center rounded-top-4 py-4" style="background-color: #081738;">
            <h3 class="mb-0 fw-bold"> Edit Kategori Produk</h3>
        </div>

        {{-- Body putih bersih --}}
        <div class="card-body bg-white p-4">
            <form action="{{ route('admin.kategori.update', $kategori->id_kategori) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Input Nama Kategori --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold text-dark" style="color: #0b1841;">Nama Kategori</label>
                    <input type="text" name="nama_kategori" class="form-control shadow-sm border-0"
                        placeholder="Masukkan nama kategori..." value="{{ $kategori->nama_kategori }}" required>
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.kategori.index') }}"
                        class="btn btn-secondary px-4 py-2 rounded-pill shadow-sm">
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-dark px-4 py-2 rounded-pill shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('admin.footer')

{{-- 🌸 Style selaras dengan form Tambah Kategori & Tambah Berita --}}
