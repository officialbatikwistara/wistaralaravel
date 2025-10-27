@include('admin.header')

<style>
  /* 🌙 Warna dasar & font */
  body {
    background-color: #f5f8fb !important;
    font-family: 'Poppins', sans-serif;
    color: #0b1841;
  }

  h2 {
    font-weight: 700;
    color: #0b1841;
  }

  /* 🌟 Tombol utama */
  .btn-dark {
    background-color: #081738 !important;
    border: none !important;
    padding: 10px 20px !important;
    border-radius: 12px !important;
    font-weight: 600 !important;
  }

  .btn-dark:hover {
    background-color: #152a6e !important;
  }

  /* 📦 Container tabel */
  .table-container {
    background: #ffffff !important;
    border-radius: 20px !important;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08) !important;
    overflow: hidden !important;
    margin-top: 25px !important;
  }

  /* 🧭 Header tabel */
  .table-header {
    background-color: #0b1841 !important;
    color: #ffffff !important;
    font-weight: 600 !important;
    font-size: 16px !important;
    letter-spacing: 0.3px;
    text-transform: capitalize;
  }

  .table-header th {
    padding: 18px !important;
    border: none !important;
  }

  /* 🪶 Baris isi tabel */
  .produk-row {
    border-bottom: 1px solid #e6e9ef !important;
    transition: all 0.25s ease;
  }

  .produk-row:hover {
    background-color: #f9fbff !important;
    transform: scale(1.002);
  }

  .produk-cell {
    padding: 20px 16px !important;
    vertical-align: middle !important;
  }

  /* 🖼️ Gambar produk */
  .produk-img {
    width: 110px !important;
    height: 110px !important;
    object-fit: cover !important;
    border-radius: 14px !important;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1) !important;
  }

  /* 🏷️ Nama & deskripsi produk */
  .produk-name {
    font-weight: 700 !important;
    color: #0b1841 !important;
    font-size: 16px !important;
  }

  .produk-desc {
    font-size: 13px !important;
    color: #6b7280 !important;
    margin-top: 3px !important;
  }

  /* 💡 Tombol aksi */
  .btn-action {
    border: none !important;
    padding: 10px 12px !important;
    border-radius: 10px !important;
    color: white !important;
    transition: all 0.3s ease !important;
  }

  .btn-edit {
    background-color: #fbbf24 !important;
  }

  .btn-edit:hover {
    background-color: #eab308 !important;
  }

  .btn-delete {
    background-color: #ef4444 !important;
  }

  .btn-delete:hover {
    background-color: #dc2626 !important;
  }

  /* 🛍️ Tombol marketplace */
  .btn-shop,
  .btn-tiktok {
    border-radius: 8px !important;
    padding: 6px 14px !important;
    font-size: 13px !important;
    text-decoration: none !important;
    font-weight: 500 !important;
    display: inline-block !important;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08) !important;
  }

  .btn-shop {
    background-color: #f59e0b !important;
    color: #fff !important;
  }

  .btn-shop:hover {
    background-color: #d97706 !important;
  }

  .btn-tiktok {
    background-color: #000 !important;
    color: #fff !important;
  }

  .btn-tiktok:hover {
    background-color: #1c1c1c !important;
  }

  /* 🔍 Input pencarian */
  .form-control {
    border-radius: 12px !important;
    border: 1px solid #d1d5db !important;
    background-color: #f9fafb !important;
    padding: 10px 16px !important;
  }

  .form-control:focus {
    border-color: #2563eb !important;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
  }

  /* 📄 Pagination */
  .pagination {
    justify-content: center !important;
  }

  .pagination .page-link {
    color: #0b1841 !important;
    border-radius: 6px !important;
  }

  .pagination .page-item.active .page-link {
    background-color: #0b1841 !important;
    border-color: #0b1841 !important;
  }

  /* 🧩 Tombol reset */
  .btn-secondary {
    background-color: #6b7280 !important;
    border: none !important;
    font-weight: 600 !important;
    border-radius: 12px !important;
    padding: 10px 20px !important;
  }

  .btn-secondary:hover {
    background-color: #4b5563 !important;
  }

  /* 📏 Header tabel lebih rapat & rata */
  table {
    width: 100% !important;
  }

  th,
  td {
    vertical-align: middle !important;
  }

  /* 🧭 Rounded header */
  .table-container thead tr:first-child th:first-child {
    border-top-left-radius: 20px !important;
  }

  .table-container thead tr:first-child th:last-child {
    border-top-right-radius: 20px !important;
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
          <th style="width:25%">Nama Produk</th>
          <th style="width:12%">Harga</th>
          <th style="width:13%">Kategori</th>
          <th style="width:18%">Marketplace</th>
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
