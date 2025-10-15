{{-- resources/views/admin/home.blade.php --}}
@include('admin.header')

<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📊 Dashboard Admin</h1>
    <a href="{{ route('admin.logout') }}" class="btn btn-danger">Logout</a>
  </div>

  <div class="row g-3">
    <div class="col-md-4">
      <div class="card p-3 shadow-sm">
        <h4>Produk</h4>
        <p>Kelola produk Batik Wistara</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card p-3 shadow-sm">
        <h4>Pesanan</h4>
        <p>Kelola transaksi & pelanggan</p>
      </div>
    </div>
  </div>
</div>

@include('admin.footer')
