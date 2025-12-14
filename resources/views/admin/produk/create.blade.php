@include('admin.header')

<link rel="stylesheet" href="{{ asset('css/admin/produk/create.css') }}">

<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-4 mx-auto" style="max-width: 900px;">
        {{-- Header biru tua --}}
        <div class="card-header text-white text-center rounded-top-4 py-4" style="background-color: #081738;">
            <h3 class="mb-0 fw-bold"> Tambah Produk Baru</h3>
        </div>

        {{-- Body --}}
        <div class="card-body bg-white p-4">
            {{-- Pesan Error --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3" role="alert">
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Form Tambah Produk --}}
            <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    {{-- Nama Produk --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Nama Produk</label>
                        <input type="text" name="nama_produk" class="form-control border-0 shadow-sm"
                            placeholder="Contoh: Batik Wistara Premium" required>
                    </div>

                    {{-- Harga --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Harga (Rp)</label>
                        <input type="number" name="harga" class="form-control border-0 shadow-sm"
                            placeholder="Masukkan harga" required>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="col-md-12">
                        <label class="form-label fw-semibold text-dark">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control border-0 shadow-sm" rows="4"
                            placeholder="Tulis deskripsi produk di sini..."></textarea>
                    </div>

                    {{-- Stok --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">Stok</label>
                        <input type="number" name="stok" class="form-control border-0 shadow-sm" value="0"
                            placeholder="Jumlah stok">
                    </div>

                    {{-- Kategori --}}
                    <div class="col-md-8">
                        <label class="form-label fw-semibold text-dark">Kategori</label>
                        <select name="id_kategori" class="form-select border-0 shadow-sm" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategori as $k)
                                <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Gambar Produk --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Gambar Produk</label>
                        <input type="file" name="gambar" class="form-control border-0 shadow-sm">
                        <small class="text-muted">Format: JPG, PNG | Maks: 2MB</small>
                    </div>

                    {{-- Link e-commerce --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Tautan E-Commerce</label>
                        <input type="url" name="link_shopee" class="form-control border-0 shadow-sm mb-2"
                            placeholder="Link Shopee (opsional)">
                        <input type="url" name="link_tiktok" class="form-control border-0 shadow-sm"
                            placeholder="Link TikTok (opsional)">
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="d-flex justify-content-end gap-2 mt-5">
                    <a href="{{ route('admin.produk.index') }}"
                        class="btn btn-secondary px-4 py-2 rounded-pill shadow-sm">
                        Kembali
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

{{-- 🌸 Style Modern & Konsisten --}}
