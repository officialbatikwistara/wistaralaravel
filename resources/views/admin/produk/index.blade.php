@include('admin.header')

<style>
  /* 🌙 Warna utama & font */
  body {
    background-color: #f3f6fa;
    font-family: 'Poppins', sans-serif;
    color: #0b1841;
  }

  h2 {
    font-weight: 700;
    color: #0b1841;
  }

  /* 🌟 Tombol Tambah Produk */
  .btn-dark {
    background-color: #0b1841 !important;
    border: none;
    padding: 10px 18px;
    border-radius: 10px;
    font-weight: 500;
  }

  .btn-dark:hover {
    background-color: #1c2755 !important;
  }

  /* 📦 Tabel container */
  .table-container {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    overflow: hidden;
  }

  /* 🧭 Header tabel */
  .table-header {
    background-color: #0b1841;
    color: #fff;
    font-weight: 600;
    text-transform: capitalize;
  }

  .table-header th {
    padding: 16px;
    font-size: 15px;
  }

  /* 🪶 Baris isi */
  .produk-row {
    background-color: #fff;
    border-bottom: 1px solid #e5e7eb;
    transition: all 0.25s ease;
  }

  .produk-row:hover {
    background-color: #f8fafc;
    transform: scale(1.005);
  }

  .produk-cell {
    padding: 18px 16px;
    vertical-align: middle;
  }

  .produk-img {
    width: 90px;
    height: 90px;
    object-fit: cover;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  }

  .produk-name {
    font-weight: 600;
    color: #0f172a;
  }

  .produk-desc {
    font-size: 13px;
    color: #6b7280;
  }

  /* 💡 Tombol Aksi */
  .btn-action {
    border: none;
    padding: 9px 11px;
    border-radius: 8px;
    color: white;
    transition: all 0.3s ease;
  }

  .btn-edit {
    background-color: #fbbf24;
  }

  .btn-edit:hover {
    background-color: #d4af37;
  }

  .btn-delete {
    background-color: #dc2626;
  }

  .btn-delete:hover {
    background-color: #b91c1c;
  }

  /* 🛍️ Link marketplace */
  .btn-shop, .btn-tiktok {
    border-radius: 6px;
    padding: 4px 10px;
    font-size: 13px;
    text-decoration: none;
    display: inline-block;
  }

  .btn-shop {
    background-color: #f59e0b;
    color: #fff;
  }

  .btn-tiktok {
    background-color: #000;
    color: #fff;
  }

  /* 🔍 Input cari */
  .form-control {
    border-radius: 8px;
    border: 1px solid #d1d5db;
    background-color: #f9fafb;
  }

  .form-control:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.2);
  }

  /* 📄 Pagination */
  .pagination {
    justify-content: center;
  }

  .pagination .page-link {
    color: #0b1841;
    border-radius: 6px;
  }

  .pagination .page-item.active .page-link {
    background-color: #0b1841;
    border-color: #0b1841;
  }
</style>

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h2>Kelola Produk</h2>
    <a href="{{ route('admin.produk.create') }}" class="btn btn-dark shadow-sm">
      + Tambah Produk
    </a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <form method="GET" action="{{ route('admin.produk.index') }}" class="row g-2 mb-4">
    <div class="col-md-8">
      <input type="text" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="🔍 Cari nama produk...">
    </div>
    <div class="col-md-4 d-flex gap-2">
      <button type="submit" class="btn btn-dark w-50">Terapkan</button>
      <a href="{{ route('admin.produk.index') }}" class="btn btn-secondary w-50">Reset</a>
    </div>
  </form>

  <div class="table-container">
    <table class="table align-middle mb-0">
      <thead class="table-header text-center">
        <tr>
          <th style="width:6%">No</th>
          <th style="width:12%">Gambar</th>
          <th style="width:22%">Nama Produk</th>
          <th style="width:13%">Harga</th>
          <th style="width:13%">Kategori</th>
          <th style="width:20%">Marketplace</th>
          <th style="width:10%">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($produk as $index => $p)
        <tr class="produk-row">
          <td class="produk-cell text-center">{{ $loop->iteration }}</td>

          <td class="produk-cell text-center">
            @if($p->gambar)
              <img src="{{ asset($p->gambar) }}" alt="Gambar produk" class="produk-img">
            @else
              <span class="text-muted fst-italic">Tidak ada</span>
            @endif
          </td>

          <td class="produk-cell">
            <div class="produk-name">{{ $p->nama_produk }}</div>
            <div class="produk-desc">{{ Str::limit($p->deskripsi, 70) }}</div>
          </td>

          <td class="produk-cell text-center">
            Rp {{ number_format($p->harga, 0, ',', '.') }}
          </td>

          <td class="produk-cell text-center">
            {{ $p->nama_kategori ?? '-' }}
          </td>

          <td class="produk-cell text-center">
            @if($p->link_shopee)
              <a href="{{ $p->link_shopee }}" target="_blank" class="btn-shop me-1">Shopee</a>
            @endif
            @if($p->link_tiktok)
              <a href="{{ $p->link_tiktok }}" target="_blank" class="btn-tiktok">TikTok</a>
            @endif
            @if(!$p->link_shopee && !$p->link_tiktok)
              <span class="text-muted">-</span>
            @endif
          </td>

          <td class="produk-cell text-center">
            <div class="d-flex justify-content-center gap-2">
              <a href="{{ route('admin.produk.edit', $p->id_produk) }}" class="btn-action btn-edit" title="Edit">
                <i class="bi bi-pencil-fill"></i>
              </a>
              <form action="{{ route('admin.produk.delete', $p->id_produk) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-action btn-delete" title="Hapus">
                  <i class="bi bi-trash-fill"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center text-muted py-3">
            Belum ada produk yang tersedia.
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if(method_exists($produk, 'links'))
    <div class="mt-3 d-flex justify-content-center">
      {{ $produk->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>

@include('admin.footer')
