@include('admin.header')

<style>
  body {
    background-color: #edf2f7;
    font-family: 'Poppins', sans-serif;
    color: #0b1841;
  }

  h2 {
    font-weight: 700;
    color: #0b1841;
  }

  /* 🌟 Container tabel */
  .table-container {
    background: #ffffff;
    border-radius: 18px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    margin-top: 20px;
  }

  /* 🧭 Header tabel */
  .table thead.table-header th {
    background-color: #081738 !important;
    color: #ffffff !important;
    font-weight: 600 !important;
    font-size: 15px !important;
    border: none !important;
    padding: 16px !important;
    text-align: center !important;
  }

  /* Buat header membulat di pojok atas */
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

  /* 📦 Baris produk */
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
    padding: 20px 16px;
    vertical-align: middle;
  }

  /* 🖼️ Gambar produk */
  .produk-img {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 16px;
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
  }

  /* 🏷️ Nama produk */
  .produk-name {
    font-weight: 600;
    font-size: 16px;
    color: #0b1841;
  }

  .produk-desc {
    font-size: 13px;
    color: #6b7280;
    margin-top: 3px;
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

  /* 🛍️ Tombol marketplace */
  .btn-shop,
  .btn-tiktok {
    border-radius: 6px;
    padding: 6px 12px;
    font-size: 13px;
    text-decoration: none;
    font-weight: 500;
    display: inline-block;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
  }

  .btn-shop {
    background-color: #f59e0b;
    color: #fff;
  }

  .btn-shop:hover {
    background-color: #d97706;
  }

  .btn-tiktok {
    background-color: #000;
    color: #fff;
  }

  .btn-tiktok:hover {
    background-color: #1c1c1c;
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

  /* 🧩 Tombol Reset dan Terapkan */
  .btn-dark {
    background-color: #081738 !important;
    border: none !important;
    border-radius: 12px !important;
    font-weight: 600 !important;
  }

  .btn-dark:hover {
    background-color: #152a6e !important;
  }

  .btn-secondary {
    background-color: #6b7280 !important;
    border: none !important;
    border-radius: 12px !important;
    font-weight: 600 !important;
  }

  .btn-secondary:hover {
    background-color: #4b5563 !important;
  }

  /* 🔍 Input search */
  .form-control {
    border-radius: 10px !important;
    border: 1px solid #cbd5e1 !important;
    padding: 10px 16px !important;
  }

  .form-control:focus {
    border-color: #2563eb !important;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
  }
</style>

@include('admin.header')

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

  {{-- 🔍 Form Pencarian --}}
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
      <thead class="table-header">
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

          {{-- 🖼️ Gambar Produk --}}
          <td class="produk-cell text-center">
           @if($p->gambar)
                <img src="{{ asset('storage/' . $p->gambar) }}" alt="Gambar produk" class="produk-img">
            @else
                <span class="text-muted fst-italic">Tidak ada</span>
            @endif

          </td>

          {{-- 🏷️ Nama dan Deskripsi Produk --}}
          <td class="produk-cell">
            <div class="produk-name">{{ $p->nama_produk }}</div>
            <div class="produk-desc">{{ Str::limit($p->deskripsi, 70) }}</div>
          </td>

          {{-- 💰 Harga --}}
          <td class="produk-cell text-center">
            Rp {{ number_format($p->harga, 0, ',', '.') }}
          </td>

          {{-- 🧭 Kategori --}}
          <td class="produk-cell text-center">
            {{ $p->kategori->nama_kategori ?? '-' }}
          </td>

          {{-- 🛍️ Link Marketplace --}}
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

          {{-- ⚙️ Tombol Aksi --}}
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

  {{-- 📄 Pagination --}}
  @if(method_exists($produk, 'links'))
    <div class="mt-3 d-flex justify-content-center">
      {{ $produk->links('pagination::bootstrap-5') }}
    </div>
  @endif
</div>

@include('admin.footer')
