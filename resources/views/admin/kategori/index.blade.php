@include('admin.header')

<div class="container py-5">
  <div class="table-container p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold mb-0"> Daftar Kategori Produk</h2>
      <a href="{{ route('admin.kategori.create') }}" class="btn btn-dark shadow-sm px-4 py-2 rounded-pill">
        <i class="fa-solid fa-plus me-2"></i> Tambah Kategori
      </a>
    </div>

    @if(session('success'))
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
                  <a href="{{ route('admin.kategori.edit', $k->id_kategori) }}" class="btn btn-action btn-edit">
                    <i class="fa-solid fa-pen"></i>
                  </a>
                  <form action="{{ route('admin.kategori.delete', $k->id_kategori) }}" method="POST" class="d-inline">
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
body {
  background-color: #edf2f7;
  font-family: 'Poppins', sans-serif;
  color: #0b1841;
}

/* 🌟 Container tabel */
.table-container {
  background: #ffffff;
  border-radius: 18px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  overflow: hidden;
}

/* 🧭 Header tabel */
.table-header th {
  background-color: #081738 !important;
  color: #ffffff !important;
  font-weight: 600 !important;
  font-size: 15px !important;
  border: none !important;
  padding: 14px !important;
  text-align: center !important;
}

/* Membulatkan header */
.table-container table {
  border-collapse: separate !important;
  border-spacing: 0 !important;
  border-radius: 18px !important;
  overflow: hidden !important;
}
.table-container thead th:first-child {
  border-top-left-radius: 18px !important;
}
.table-container thead th:last-child {
  border-top-right-radius: 18px !important;
}

/* 📦 Baris kategori */
.produk-row {
  border-bottom: 1px solid #e5e7eb;
  transition: 0.25s ease;
}
.produk-row:hover {
  background-color: #f9fafb;
  transform: scale(1.001);
}

/* 📋 Isi tabel */
.produk-cell {
  padding: 16px 14px;
  vertical-align: middle;
}

/* 🏷️ Nama kategori */
.produk-name {
  font-weight: 600;
  font-size: 16px;
  color: #0b1841;
}

/* ✏️ Tombol aksi */
.btn-action {
  border: none;
  padding: 10px 12px;
  border-radius: 8px;
  color: white;
  transition: 0.3s ease;
}
.btn-edit {
  background-color: #fbbf24;
}
.btn-edit:hover {
  background-color: #d1a106;
}
.btn-delete {
  background-color: #dc2626;
}
.btn-delete:hover {
  background-color: #b91c1c;
}

/* 🧩 Tombol utama */
.btn-dark {
  background-color: #081738 !important;
  border: none !important;
  border-radius: 12px !important;
  font-weight: 600 !important;
}
.btn-dark:hover {
  background-color: #152a6e !important;
}
</style>
