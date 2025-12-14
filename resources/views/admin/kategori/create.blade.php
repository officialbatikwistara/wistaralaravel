@include('admin.header')

<link rel="stylesheet" href="{{ asset('css/kategori/create.css') }}">

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
                    <input type="text" name="nama_kategori" class="form-control shadow-sm border-0"
                        placeholder="Masukkan nama kategori..." required>
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.kategori.index') }}"
                        class="btn btn-secondary px-4 py-2 rounded-pill shadow-sm">
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
