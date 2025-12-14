@include('admin.header')
<link rel="stylesheet" href="{{ asset('css/admin/berita/index.css') }}">
<style>
    /* 🌄 Background fullscreen solid */
    body {
        font-family: 'Poppins', sans-serif;
        color: #0b1841;
        margin: 0;
        min-height: 100vh;
        background: url('{{ asset('img/background1.svg') }}') no-repeat center center fixed;
        background-size: cover;
    }
</style>

{{-- =========================
    🌸 MAIN CONTENT
========================= --}}
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Kelola Berita</h2>
        <a href="{{ route('admin.berita.create') }}" class="btn btn-dark shadow-sm px-4 py-2 rounded-pill">
            <i class="fa-solid fa-plus me-2"></i> Tambah Berita
        </a>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- TABLE --}}
    <div class="table-container">
        <table class="table align-middle text-center">
            <thead class="table-header">
                <tr>
                    <th>No</th>
                    <th>Gambar</th>
                    <th>Judul</th>
                    <th>Sumber</th>
                    <th>Tanggal</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($berita as $index => $b)
                    <tr class="produk-row">
                        <td class="produk-cell">{{ $index + 1 }}</td>
                        <td class="produk-cell">
                            @if ($b->gambar)
                                <img src="{{ asset($b->gambar) }}" alt="Gambar Berita" class="produk-img">
                            @else
                                <span class="text-muted">Tidak ada</span>
                            @endif
                        </td>
                        <td class="produk-cell produk-name">{{ $b->judul }}</td>
                        <td class="produk-cell">
                            @if ($b->sumber)
                                @if ($b->tautan_sumber)
                                    <a href="{{ $b->tautan_sumber }}" target="_blank"
                                        class="text-decoration-none text-primary fw-semibold">
                                        {{ $b->sumber }}
                                    </a>
                                @else
                                    {{ $b->sumber }}
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="produk-cell fw-semibold">
                            {{ \Carbon\Carbon::parse($b->tanggal)->format('d M Y') }}
                        </td>
                        <td class="produk-cell text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.berita.edit', $b->id) }}" class="btn btn-action btn-edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.berita.delete', $b->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-action btn-delete"
                                        onclick="return confirm('Yakin ingin menghapus berita ini?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fa-solid fa-folder-open fa-2x mb-2"></i><br>
                            Belum ada berita
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@include('admin.footer')
