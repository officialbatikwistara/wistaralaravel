@include('admin.header')

<style>
  body {
    background-color: #ffffff; /* background putih */
    color: #000000; /* teks utama hitam */
  }

  h2 {
    color: #000000;
  }

  /* Tabel */
  .table {
    border: 1px solid #dee2e6;
  }

  .table thead {
    background-color: #1e3a8a; /* navy gelap */
    color: white; /* teks putih */
  }

  .table tbody tr {
    background-color: #ffffff; /* baris putih */
    color: #000000;
  }

  .table tbody tr:hover {
    background-color: #f1f5f9; /* efek hover lembut */
  }

  .table th, .table td {
    vertical-align: middle;
  }

  /* Tombol */
  .btn-dark {
    background-color: #1e3a8a;
    border: none;
  }

  .btn-dark:hover {
    background-color: #172554;
  }

  .btn-secondary {
    background-color: #6b7280;
    border: none;
  }

  .btn-secondary:hover {
    background-color: #4b5563;
  }

  .btn-primary {
    background-color: #2563eb;
    border: none;
  }

  .btn-primary:hover {
    background-color: #1d4ed8;
  }

  .btn-danger {
    background-color: #dc2626;
    border: none;
  }

  .btn-danger:hover {
    background-color: #b91c1c;
  }

  /* Form input */
  .form-control, .form-select {
    background-color: #f9fafb;
    color: #000000;
    border: 1px solid #d1d5db;
  }

  .form-control::placeholder {
    color: #6b7280;
  }

  /* Card bayangan */
  .table-responsive {
    border-radius: 0.5rem;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  }
</style>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h2>📦 Kelola Produk</h2>
    <a href="{{ route('admin.produk.create') }}" class="btn btn-dark">+ Tambah Produk</a>
  </div>

  {{-- Notifikasi sukses --}}
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  {{-- Form pencarian --}}
  <form method="GET" action="{{ route('admin.produk.index') }}" class="row g-2 mb-4">
    <div class="col-md-8">
      <input
        type="text"
        name="cari"
        value="{{ request('cari') }}"
        class="form-control"
        placeholder="🔍 Cari nama produk..."
      >
    </div>
    <div class="col-md-4 d-flex gap-2">
      <button type="submit" class="btn btn-dark w-50">Terapkan</button>
      <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary w-50">Reset</a>
    </div>
  </form>

  {{-- Tabel produk --}}
  <div class="table-responsive shadow-sm rounded">
    <table class="table table-bordered align-middle mb-0">
      <thead>
        <tr>
          <th style="width:5%">#</th>
          <th style="width:10%">Gambar</th>
          <th>Nama Produk</th>
          <th style="width:15%">Harga</th>
          <th style="width:15%">Kategori</th>
          <th style="width:10%">Shopee</th>
          <th style="width:10%">TikTok</th>
          <th style="width:15%">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($produk as $p)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>
              @if($p->gambar)
                <img src="{{ asset($p->gambar) }}" alt="Gambar produk" width="60" class="rounded shadow-sm">
              @else
                <span class="text-muted fst-italic">Tidak ada</span>
              @endif
            </td>
            <td>
              <strong>{{ $p->nama_produk }}</strong><br>
              <small class="text-muted">{{ Str::limit($p->deskripsi, 60) }}</small>
            </td>
            <td>Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
            <td>{{ $p->nama_kategori ?? '-' }}</td>
            <td class="text-center">
              @if($p->link_shopee)
                <a href="{{ $p->link_shopee }}" target="_blank" class="btn btn-warning btn-sm">Shopee</a>
              @else
                <span class="text-muted">-</span>
              @endif
            </td>
            <td class="text-center">
              @if($p->link_tiktok)
                <a href="{{ $p->link_tiktok }}" target="_blank" class="btn btn-dark btn-sm">TikTok</a>
              @else
                <span class="text-muted">-</span>
              @endif
            </td>
            <td class="text-center">
              <div class="d-flex justify-content-center gap-2">
                <a href="{{ route('admin.produk.edit', $p->id_produk) }}" class="btn btn-primary btn-sm">Edit</a>
                <form action="{{ route('admin.produk.delete', $p->id_produk) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center text-muted py-3">Belum ada produk yang tersedia.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Pagination --}}
  @if(method_exists($produk, 'links'))
    <div class="mt-3 d-flex justify-content-center">
      {{ $produk->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>

@include('admin.footer')
