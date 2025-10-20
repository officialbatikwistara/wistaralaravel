@include('admin.header')

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">📰 Kelola Berita</h2>
        <a href="{{ route('admin.berita.create') }}" class="btn btn-dark shadow-sm">
            <i class="fa-solid fa-plus me-2"></i> Tambah Berita
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 130px;">Gambar</th>
                        <th>Judul</th>
                        <th>Sumber</th>
                        <th>Tanggal</th>
                        <th style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($berita as $b)
                        <tr>
                            <td>
                                @if($b->gambar)
                                    <img src="{{ asset($b->gambar) }}" class="img-fluid rounded shadow-sm" alt="Gambar Berita">
                                @else
                                    <span class="text-muted">Tidak ada</span>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $b->judul }}</td>
                            <td>
                                @if($b->sumber)
                                    @if($b->tautan_sumber)
                                        <a href="{{ $b->tautan_sumber }}" target="_blank" class="text-decoration-none">
                                            {{ $b->sumber }}
                                        </a>
                                    @else
                                        {{ $b->sumber }}
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($b->tanggal)->format('d M Y') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.berita.edit', $b->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form action="{{ route('admin.berita.delete', $b->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus berita ini?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada berita</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('admin.footer')
