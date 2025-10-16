@include('admin.header')

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">📰 Kelola Berita</h2>
        <a href="{{ route('admin.berita.create') }}" class="btn btn-dark">
            <i class="fa-solid fa-plus me-2"></i> Tambah Berita
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>Gambar</th>
                <th>Judul</th>
                <th>Sumber</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($berita as $b)
            <tr>
                <td style="width: 120px">
                    @if($b->gambar)
                        <img src="{{ asset($b->gambar) }}" class="img-fluid rounded">
                    @endif
                </td>
                <td>{{ $b->judul }}</td>
                <td>
                    @if($b->sumber)
                        @if($b->tautan_sumber)
                            <a href="{{ $b->tautan_sumber }}" target="_blank">{{ $b->sumber }}</a>
                        @else
                            {{ $b->sumber }}
                        @endif
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($b->tanggal)->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('admin.berita.edit', $b->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.berita.delete', $b->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus berita ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-muted">Belum ada berita</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@include('admin.footer')
