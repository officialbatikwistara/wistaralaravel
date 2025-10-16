{{-- resources/views/admin/home.blade.php --}}
@include('admin.header')

<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold">📊 Dashboard Admin</h1>
    <a href="{{ route('admin.logout') }}" class="btn btn-danger">
      <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
    </a>
  </div>

  <div class="row g-4">
    <!-- Produk -->
    <div class="col-md-4">
      <div class="card p-4 shadow-sm h-100 border-0">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h4 class="mb-0 fw-bold">🧵 Produk</h4>
          <i class="fa-solid fa-shirt fa-lg text-secondary"></i>
        </div>
        <p class="text-muted">Kelola katalog produk Batik Wistara, tambah & ubah koleksi.</p>
        <a href="{{ url('/admin/produk') }}" class="btn btn-dark w-100">
          Kelola Produk
        </a>
      </div>
    </div>

    <!-- Pesanan -->
    <div class="col-md-4">
      <div class="card p-4 shadow-sm h-100 border-0">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h4 class="mb-0 fw-bold">📦 Pesanan</h4>
          <i class="fa-solid fa-bag-shopping fa-lg text-secondary"></i>
        </div>
        <p class="text-muted">Lihat dan kelola transaksi serta status pesanan pelanggan.</p>
        <a href="{{ url('/admin/pesanan') }}" class="btn btn-dark w-100">
          Kelola Pesanan
        </a>
      </div>
    </div>

    <!-- Berita -->
    <div class="col-md-4">
      <div class="card p-4 shadow-sm h-100 border-0">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h4 class="mb-0 fw-bold">📰 Berita</h4>
          <i class="fa-solid fa-newspaper fa-lg text-secondary"></i>
        </div>
        <p class="text-muted">Posting informasi & artikel terbaru untuk pengguna.</p>
        <a href="{{ url('/admin/berita') }}" class="btn btn-dark w-100">
          Kelola Berita
        </a>
      </div>
    </div>
  </div>
</div>

@include('admin.footer')
