@include('admin.header')

<link rel="stylesheet" href="{{ asset('css/kategori/index.css') }}">

<div class="container py-5">
    <div class="table-container p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Daftar Kategori Produk</h2>
            <a href="{{ route('admin.kategori.create') }}" class="btn btn-dark shadow-sm px-4 py-2 rounded-pill">
                <i class="fa-solid fa-plus me-2"></i> Tambah Kategori
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success shadow-sm mb-4">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table align-middle text-center mb-0">
                <thead class="table-header">
                    <tr>
                        <th style="width: 8%">No</th>
                        <th style="width: 40%">Nama Kategori</th>
                        <th style="width: 32%">Slug</th>
                        <th style="width: 20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategori as $k)
                        <tr class="produk-row">
                            <td class="produk-cell">{{ $loop->iteration }}</td>
                            <td class="produk-cell produk-name">{{ $k->nama_kategori }}</td>
                            <td class="produk-cell text-muted">{{ $k->slug }}</td>
                            <td class="produk-cell">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.kategori.edit', $k->id_kategori) }}"
                                        class="btn btn-action btn-edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form action="{{ route('admin.kategori.delete', $k->id_kategori) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-action btn-delete"
                                            onclick="return confirm('Hapus kategori ini?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="fa-solid fa-folder-open fa-2x mb-2"></i><br>
                                Belum ada kategori
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('admin.footer')

<style>
    /* 🌄 Background Fullscreen & Solid */
    body {
        font-family: 'Poppins', sans-serif;
        color: #0b1841;
        margin: 0;
        min-height: 100vh;
        background:
            linear-gradient(rgba(255, 255, 255, 0.25), rgba(245, 247, 255, 0.25)),
            url('{{ asset('img/background1.svg') }}') no-repeat center center fixed;
        background-size: cover;
    }
</style>
