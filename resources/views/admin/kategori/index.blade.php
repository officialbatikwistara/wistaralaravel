@include('admin.header')

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>📂 Kategori Produk</h2>
    <a href="{{ route('admin.kategori.create') }}" class="btn btn-dark">+ Tambah Kategori</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <table class="table table-bordered align-middle">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Nama Kategori</th>
        <th>Slug</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($kategori as $k)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $k->nama_kategori }}</td>
          <td>{{ $k->slug }}</td>
          <td>
            <a href="{{ route('admin.kategori.edit', $k->id_kategori) }}" class="btn btn-primary btn-sm">Edit</a>
            <form action="{{ route('admin.kategori.delete', $k->id_kategori) }}" method="POST" class="d-inline">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus kategori ini?')">Hapus</button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="4" class="text-center text-muted">Belum ada kategori</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

@include('admin.footer')
