@include('admin.header')

<div class="container py-5">
  <div class="card shadow-lg border-0 rounded-4 p-4 bg-light">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold mb-0">Daftar Kategori Produk</h2>
      <a href="{{ route('admin.kategori.create') }}" class="btn btn-dark px-4 py-2 rounded-pill shadow-sm">
        <i class="fa-solid fa-plus me-1"></i> Tambah Kategori
      </a>
    </div>

    @if(session('success'))
      <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-dark text-center">
          <tr>
            <th style="width: 5%">No</th>
            <th style="width: 35%">Nama Kategori</th>
            <th style="width: 35%">Slug</th>
            <th style="width: 25%">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($kategori as $k)
            <tr class="table-row-hover">
              <td class="text-center">{{ $loop->iteration }}</td>
              <td>{{ $k->nama_kategori }}</td>
              <td class="text-muted">{{ $k->slug }}</td>
              <td class="text-center">
                <a href="{{ route('admin.kategori.edit', $k->id_kategori) }}"
                   class="btn btn-outline-primary btn-sm px-3 me-1">
                  <i class="fa-solid fa-pen-to-square"></i> Edit
                </a>
                <form action="{{ route('admin.kategori.delete', $k->id_kategori) }}"
                      method="POST" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                          class="btn btn-outline-danger btn-sm px-3"
                          onclick="return confirm('Hapus kategori ini?')">
                    <i class="fa-solid fa-trash"></i> Hapus
                  </button>
                </form>
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
  .table-row-hover:hover {
    background-color: #f1f3f5 !important;
    transition: 0.2s ease-in-out;
  }

  .btn-outline-primary:hover,
  .btn-outline-danger:hover {
    color: #fff !important;
  }

  .card {
    animation: fadeIn 0.5s ease;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
  }
</style>
